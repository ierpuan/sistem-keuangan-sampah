<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\TransaksiPembayaran;
use App\Models\DepositPelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function create($id_tagihan)
    {
        $tagihan = Tagihan::with('pelanggan.deposit')->findOrFail($id_tagihan);

        if ($tagihan->is_lunas) {
            return redirect()->route('tagihan.index')
                           ->with('error', 'Tagihan sudah lunas.');
        }

        return view('pembayaran.create', compact('tagihan'));
    }

    public function edit($id)
    {
        $transaksi = TransaksiPembayaran::with(['tagihan.pelanggan.deposit'])->findOrFail($id);

        return view('pembayaran.edit', compact('transaksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_tagihan'      => 'required|exists:tagihan,id_tagihan',
            'jml_bayar_input' => 'nullable|numeric|min:0',
            'gunakan_deposit' => 'nullable|in:1',
        ]);

        $tagihan   = Tagihan::with('pelanggan.deposit')->findOrFail($request->id_tagihan);
        $pelanggan = $tagihan->pelanggan;
        $deposit   = $pelanggan->deposit;

        $bayarTunai   = (float) ($request->jml_bayar_input ?? 0);
        $pakaiDeposit = $request->gunakan_deposit == '1';

        if ($bayarTunai <= 0 && !$pakaiDeposit) {
            return back()->withErrors([
                'jml_bayar_input' => 'Isi pembayaran atau gunakan deposit'
            ]);
        }

        $sisaTagihan = $tagihan->jml_tagihan_pokok - $tagihan->total_sudah_bayar;

        DB::beginTransaction();
        try {

            // ======================
            // 1. PEMBAYARAN TUNAI
            // ======================
            if ($bayarTunai > 0) {
                $bayarMasukTagihan = min($bayarTunai, $sisaTagihan);
                $kelebihanTunai = $bayarTunai - $bayarMasukTagihan;

                TransaksiPembayaran::create([
                    'id_tagihan'              => $tagihan->id_tagihan,
                    'id_pengguna'             => Auth::id(),
                    'jml_bayar_input'         => $bayarTunai,
                    'jml_bayar_dari_deposit'  => 0, // Tidak dari deposit
                ]);

                $tagihan->total_sudah_bayar += $bayarMasukTagihan;
                $sisaTagihan -= $bayarMasukTagihan;

                // Simpan kelebihan tunai ke deposit
                if ($kelebihanTunai > 0) {
                    if (!$deposit) {
                        $deposit = DepositPelanggan::create([
                            'id_pelanggan'  => $pelanggan->id_pelanggan,
                            'saldo_deposit' => 0,
                        ]);
                    }
                    $deposit->saldo_deposit += $kelebihanTunai;
                    $deposit->save();
                }
            }

            // ======================
            // 2. PEMBAYARAN DEPOSIT
            // ======================
            if ($pakaiDeposit && $deposit && $deposit->saldo_deposit > 0 && $sisaTagihan > 0) {
                $depositDipakai = min($deposit->saldo_deposit, $sisaTagihan);

                // Kurangi deposit
                $deposit->saldo_deposit -= $depositDipakai;
                $deposit->save();

                // Catat transaksi dari deposit
                TransaksiPembayaran::create([
                    'id_tagihan'              => $tagihan->id_tagihan,
                    'id_pengguna'             => Auth::id(),
                    'jml_bayar_input'         => $depositDipakai,
                    'jml_bayar_dari_deposit'  => $depositDipakai, // DARI DEPOSIT
                ]);

                $tagihan->total_sudah_bayar += $depositDipakai;
            }

            // ======================
            // 3. UPDATE STATUS
            // ======================
            $tagihan->save();
            $tagihan->updateStatus();

            DB::commit();

            return redirect()
                ->route('tagihan.show', $tagihan->id_tagihan)
                ->with('success', 'Pembayaran berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jml_bayar_input' => 'nullable|numeric|min:1',
            'gunakan_deposit' => 'nullable|in:1',
        ]);

        DB::beginTransaction();
        try {
            $transaksi = TransaksiPembayaran::with('tagihan.pelanggan.deposit')->findOrFail($id);
            $tagihan   = $transaksi->tagihan;
            $pelanggan = $tagihan->pelanggan;
            $deposit   = $pelanggan->deposit;

            $lama = (float) $transaksi->jml_bayar_input;
            $baru = (float) $request->jml_bayar_input;
            $depositLama = (float) ($transaksi->jml_bayar_dari_deposit ?? 0);
            $pakaiDeposit = $request->gunakan_deposit == '1';

            // ======================
            // 1. ROLLBACK LAMA
            // ======================
            // Kembalikan pembayaran lama
            $tagihan->total_sudah_bayar -= $lama;
            $tagihan->total_sudah_bayar = max(0, $tagihan->total_sudah_bayar);

            // Kembalikan deposit lama jika ada
            if ($depositLama > 0 && $deposit) {
                $deposit->saldo_deposit += $depositLama;
                $deposit->save();
            }

            // ======================
            // 2. TERAPKAN BARU
            // ======================
            $sisaTagihan = $tagihan->jml_tagihan_pokok - $tagihan->total_sudah_bayar;
            $depositBaru = 0;

            // Cek apakah pakai deposit baru
            if ($pakaiDeposit && $deposit && $deposit->saldo_deposit > 0) {
                $depositBaru = min($deposit->saldo_deposit, min($baru, $sisaTagihan));

                // Kurangi deposit
                $deposit->saldo_deposit -= $depositBaru;
                $deposit->save();
            }

            $masukTagihan = min($baru, $sisaTagihan);
            $kelebihan = $baru - $masukTagihan;

            $tagihan->total_sudah_bayar += $masukTagihan;

            // Simpan kelebihan ke deposit
            if ($kelebihan > 0) {
                if (!$deposit) {
                    $deposit = DepositPelanggan::create([
                        'id_pelanggan'  => $pelanggan->id_pelanggan,
                        'saldo_deposit' => 0,
                    ]);
                }
                $deposit->saldo_deposit += $kelebihan;
                $deposit->save();
            }

            // ======================
            // 3. UPDATE TRANSAKSI
            // ======================
            $transaksi->jml_bayar_input = $baru;
            $transaksi->jml_bayar_dari_deposit = $depositBaru;
            $transaksi->id_pengguna = Auth::id();
            $transaksi->save();

            // ======================
            // 4. UPDATE STATUS
            // ======================
            $tagihan->save();
            $tagihan->updateStatus();

            DB::commit();

            return redirect()
                ->route('tagihan.show', $tagihan->id_tagihan)
                ->with('success', 'Pembayaran berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $transaksi = TransaksiPembayaran::with('tagihan.pelanggan.deposit')
                ->findOrFail($id);

            $tagihan = $transaksi->tagihan;
            $deposit = optional($tagihan->pelanggan)->deposit;
            $jumlahBayar = (float) $transaksi->jml_bayar_input;
            $dariDeposit = (float) ($transaksi->jml_bayar_dari_deposit ?? 0);

            // ======================
            // 1. Rollback pembayaran tagihan
            // ======================
            $tagihan->total_sudah_bayar -= $jumlahBayar;
            $tagihan->total_sudah_bayar = max(0, $tagihan->total_sudah_bayar);

            // ======================
            // 2. Kembalikan deposit jika pembayaran dari deposit
            // ======================
            if ($dariDeposit > 0 && $deposit) {
                $deposit->saldo_deposit += $dariDeposit;
                $deposit->save();
            }

            // ======================
            // 3. Hapus transaksi pembayaran
            // ======================
            $transaksi->delete();

            // ======================
            // 4. Update status tagihan
            // ======================
            $tagihan->save();
            $tagihan->updateStatus();

            DB::commit();

            return redirect()
                ->route('tagihan.show', $tagihan->id_tagihan)
                ->with('success', 'Transaksi pembayaran berhasil dihapus dan deposit dikembalikan');

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => 'Gagal menghapus transaksi: ' . $e->getMessage()
            ]);
        }
    }
}