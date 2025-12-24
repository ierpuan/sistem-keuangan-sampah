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
        'id_tagihan' => 'required|exists:tagihan,id_tagihan',
        'jml_bayar_input' => [
            'nullable',
            'numeric',
            'min:0',
            function ($attribute, $value, $fail) {
                // Cek jika ada tanda titik atau koma
                if ($value !== null && (strpos((string)$value, '.') !== false || strpos((string)$value, ',') !== false)) {
                    $fail('Jumlah pembayaran tidak boleh menggunakan tanda titik atau koma. Masukkan angka bulat saja (misal: 10000).');
                }
            },
        ],
        'gunakan_deposit' => 'nullable|in:1',
    ]);

    $tagihan   = Tagihan::with('pelanggan.deposit')->findOrFail($request->id_tagihan);
    $pelanggan = $tagihan->pelanggan;
    $deposit   = $pelanggan->deposit;

    $bayarTunai   = (float) ($request->jml_bayar_input ?? 0);
    $pakaiDeposit = $request->gunakan_deposit == '1';

    // Validasi: harus ada pembayaran tunai ATAU gunakan deposit
    if ($bayarTunai <= 0 && !$pakaiDeposit) {
        return back()->withErrors([
            'jml_bayar_input' => 'Isi jumlah pembayaran atau centang "Gunakan saldo deposit untuk membayar"'
        ])->withInput();
    }

    // Validasi: cek jika input mengandung desimal
    if ($bayarTunai > 0 && floor($bayarTunai) != $bayarTunai) {
        return back()->withErrors([
            'jml_bayar_input' => 'Jumlah pembayaran harus berupa angka bulat tanpa desimal'
        ])->withInput();
    }

    $sisaTagihan = $tagihan->jml_tagihan_pokok - $tagihan->total_sudah_bayar;

    DB::beginTransaction();
    try {
        $depositDigunakan = 0;

        // ======================
        // 1. PROSES PEMBAYARAN TUNAI (jika ada)
        // ======================
        if ($bayarTunai > 0) {
            $bayarMasukTagihan = min($bayarTunai, $sisaTagihan);
            $kelebihanTunai = $bayarTunai - $bayarMasukTagihan;

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
        // 2. PROSES PEMBAYARAN DARI DEPOSIT (jika dicentang dan masih ada sisa)
        // ======================
        if ($pakaiDeposit && $deposit && $deposit->saldo_deposit > 0 && $sisaTagihan > 0) {
            $depositDigunakan = min($deposit->saldo_deposit, $sisaTagihan);

            // Kurangi saldo deposit
            $deposit->saldo_deposit -= $depositDigunakan;
            $deposit->save();

            // Tambahkan ke pembayaran tagihan
            $tagihan->total_sudah_bayar += $depositDigunakan;
        }

        // ======================
        // 3. BUAT 1 TRANSAKSI GABUNGAN (tunai + deposit)
        // ======================
        TransaksiPembayaran::create([
            'id_tagihan'              => $tagihan->id_tagihan,
            'id_pengguna'             => Auth::id(),
            'jml_bayar_input'         => $bayarTunai + $depositDigunakan,
            'jml_bayar_dari_deposit'  => $depositDigunakan,
        ]);

        // ======================
        // 4. UPDATE STATUS TAGIHAN
        // ======================
        $tagihan->save();
        $tagihan->updateStatus();

        DB::commit();

        return redirect()
            ->route('tagihan.show', $tagihan->id_tagihan)
            ->with('success', 'Pembayaran berhasil disimpan');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()])->withInput();
    }
}

public function update(Request $request, $id)
{
    $request->validate([
        'jml_bayar_input' => [
            'nullable',
            'numeric',
            'min:0',
            function ($attribute, $value, $fail) {
                // Cek jika ada tanda titik atau koma
                if ($value !== null && (strpos((string)$value, '.') !== false || strpos((string)$value, ',') !== false)) {
                    $fail('Jumlah pembayaran tidak boleh menggunakan tanda titik atau koma. Masukkan angka bulat saja (misal: 10000).');
                }
            },
        ],
        'gunakan_deposit' => 'nullable|in:1',
    ]);

    DB::beginTransaction();
    try {
        $transaksi = TransaksiPembayaran::with('tagihan.pelanggan.deposit')->findOrFail($id);
        $tagihan   = $transaksi->tagihan;
        $pelanggan = $tagihan->pelanggan;
        $deposit   = $pelanggan->deposit;

        $inputBayarBaru = (float) ($request->jml_bayar_input ?? 0);
        $pakaiDeposit = $request->gunakan_deposit == '1';

        // Validasi: harus ada input atau menggunakan deposit
        if ($inputBayarBaru <= 0 && !$pakaiDeposit) {
            return back()->withErrors([
                'jml_bayar_input' => 'Isi jumlah pembayaran atau centang "Gunakan saldo deposit untuk membayar"'
            ])->withInput();
        }

        // Validasi: cek jika input mengandung desimal
        if ($inputBayarBaru > 0 && floor($inputBayarBaru) != $inputBayarBaru) {
            return back()->withErrors([
                'jml_bayar_input' => 'Jumlah pembayaran harus berupa angka bulat tanpa desimal'
            ])->withInput();
        }

        $jmlBayarLama = (float) $transaksi->jml_bayar_input;
        $depositDigunakanLama = (float) ($transaksi->jml_bayar_dari_deposit ?? 0);

        // ======================
        // 1. ROLLBACK TRANSAKSI LAMA
        // ======================
        // Kembalikan pembayaran ke tagihan
        $tagihan->total_sudah_bayar -= $jmlBayarLama;
        $tagihan->total_sudah_bayar = max(0, $tagihan->total_sudah_bayar);

        // Kembalikan deposit yang digunakan sebelumnya
        if ($depositDigunakanLama > 0 && $deposit) {
            $deposit->saldo_deposit += $depositDigunakanLama;
            $deposit->save();
        }

        // ======================
        // 2. HITUNG SISA TAGIHAN SETELAH ROLLBACK
        // ======================
        $sisaTagihan = $tagihan->jml_tagihan_pokok - $tagihan->total_sudah_bayar;

        // ======================
        // 3. TERAPKAN PEMBAYARAN BARU
        // ======================
        $depositDigunakan = 0;
        $bayarTunai = $inputBayarBaru;

        // A. PROSES PEMBAYARAN TUNAI DULU (jika ada)
        if ($bayarTunai > 0) {
            $bayarMasukTagihan = min($bayarTunai, $sisaTagihan);
            $kelebihanTunai = $bayarTunai - $bayarMasukTagihan;

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

        // B. PROSES PEMBAYARAN DARI DEPOSIT (jika dicentang dan masih ada sisa tagihan)
        if ($pakaiDeposit && $deposit && $deposit->saldo_deposit > 0 && $sisaTagihan > 0) {
            $depositDigunakan = min($deposit->saldo_deposit, $sisaTagihan);

            // Kurangi saldo deposit
            $deposit->saldo_deposit -= $depositDigunakan;
            $deposit->save();

            // Tambahkan ke pembayaran tagihan
            $tagihan->total_sudah_bayar += $depositDigunakan;
        }

        // ======================
        // 4. UPDATE TRANSAKSI
        // ======================
        // Total bayar input = tunai + deposit yang digunakan
        $transaksi->jml_bayar_input = $bayarTunai + $depositDigunakan;
        $transaksi->jml_bayar_dari_deposit = $depositDigunakan;
        $transaksi->id_pengguna = Auth::id();
        $transaksi->save();

        // ======================
        // 5. UPDATE STATUS TAGIHAN
        // ======================
        $tagihan->save();
        $tagihan->updateStatus();

        DB::commit();

        return redirect()
            ->route('tagihan.show', $tagihan->id_tagihan)
            ->with('success', 'Pembayaran berhasil diupdate');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()])->withInput();
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
                ->with('success', 'Transaksi pembayaran berhasil dihapus');

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => 'Gagal menghapus transaksi: ' . $e->getMessage()
            ]);
        }
    }
}