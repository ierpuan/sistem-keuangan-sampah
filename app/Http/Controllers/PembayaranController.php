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

    public function update(Request $request, $id)
{
    $request->validate([
        'jml_bayar_input' => 'required|numeric|min:1',
    ]);

    DB::beginTransaction();
    try {
        $transaksi = TransaksiPembayaran::with('tagihan.pelanggan.deposit')->findOrFail($id);
        $tagihan   = $transaksi->tagihan;
        $deposit   = $tagihan->pelanggan->deposit;

        $lama = (float) $transaksi->jml_bayar_input;
        $baru = (float) $request->jml_bayar_input;

        if ($lama == $baru) {
            DB::rollBack();
            return back()->with('info', 'Tidak ada perubahan');
        }

        // ======================
        // 1. ROLLBACK LAMA
        // ======================
        $tagihan->total_sudah_bayar -= $lama;
        $tagihan->total_sudah_bayar = max(0, $tagihan->total_sudah_bayar);

        // ======================
        // 2. TERAPKAN BARU
        // ======================
        $sisa = $tagihan->jml_tagihan_pokok - $tagihan->total_sudah_bayar;
        $masukTagihan = min($baru, $sisa);
        $kelebihan    = $baru - $masukTagihan;

        $tagihan->total_sudah_bayar += $masukTagihan;

        if ($kelebihan > 0) {
            if (! $deposit) {
                $deposit = DepositPelanggan::create([
                    'id_pelanggan'  => $tagihan->pelanggan->id_pelanggan,
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
        $transaksi->id_pengguna     = Auth::id();
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
        $deposit = $tagihan->pelanggan->deposit;

        $jumlah = (float) $transaksi->jml_bayar_input;

        // ======================
        // 1. ROLLBACK TAGIHAN
        // ======================
        $tagihan->total_sudah_bayar -= $jumlah;
        $tagihan->total_sudah_bayar = max(0, $tagihan->total_sudah_bayar);

        // ======================
        // 2. JIKA ADA KELEBIHAN → TARIK DARI DEPOSIT
        // ======================
        $sisaSetelahRollback =
            $tagihan->jml_tagihan_pokok - $tagihan->total_sudah_bayar;

        if ($sisaSetelahRollback < 0 && $deposit) {
            // artinya dulu ada kelebihan masuk deposit
            $deposit->saldo_deposit += $sisaSetelahRollback; // nilai negatif
            $deposit->saldo_deposit = max(0, $deposit->saldo_deposit);
            $deposit->save();
        }

        // ======================
        // 3. HAPUS TRANSAKSI
        // ======================
        $transaksi->delete();

        // ======================
        // 4. UPDATE STATUS
        // ======================
        $tagihan->save();
        $tagihan->updateStatus();

        DB::commit();

        return redirect()
            ->route('tagihan.show', $tagihan->id_tagihan)
            ->with('success', 'Transaksi pembayaran berhasil dihapus');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()]);
    }
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

    $bayarTunai   = (float) $request->jml_bayar_input;
    $pakaiDeposit = $request->gunakan_deposit == '1';

    if ($bayarTunai <= 0 && ! $pakaiDeposit) {
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
        $kelebihanTunai = 0;
        $bayarMasukTagihan = min($bayarTunai, $sisaTagihan);

        if ($bayarMasukTagihan > 0) {
            TransaksiPembayaran::create([
                'id_tagihan'      => $tagihan->id_tagihan,
                'id_pengguna'     => Auth::id(),
                'jml_bayar_input' => $bayarMasukTagihan,
            ]);

            $tagihan->total_sudah_bayar += $bayarMasukTagihan;
            $sisaTagihan -= $bayarMasukTagihan;
        }

        if ($bayarTunai > $bayarMasukTagihan) {
            $kelebihanTunai = $bayarTunai - $bayarMasukTagihan;
        }

        // ======================
        // 2. PEMBAYARAN DEPOSIT
        // ======================
        if ($pakaiDeposit && $deposit && $sisaTagihan > 0) {

            $depositDipakai = min($deposit->saldo_deposit, $sisaTagihan);

            if ($depositDipakai > 0) {
                $deposit->saldo_deposit -= $depositDipakai;
                $deposit->save();

                TransaksiPembayaran::create([
                    'id_tagihan'      => $tagihan->id_tagihan,
                    'id_pengguna'     => Auth::id(),
                    'jml_bayar_input' => $depositDipakai,
                ]);

                $tagihan->total_sudah_bayar += $depositDipakai;
            }
        }

        // ======================
        // 3. SIMPAN KELEBIHAN
        // ======================
        if ($kelebihanTunai > 0) {
            if (! $deposit) {
                $deposit = DepositPelanggan::create([
                    'id_pelanggan'  => $pelanggan->id_pelanggan,
                    'saldo_deposit' => 0,
                ]);
            }

            $deposit->saldo_deposit += $kelebihanTunai;
            $deposit->save();
        }

        // ======================
        // 4. UPDATE STATUS
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
}