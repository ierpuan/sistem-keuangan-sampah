<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tagihan;
use App\Models\TransaksiPembayaran;
use App\Models\Pengguna;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AutoDebitDeposit extends Command
{
    protected $signature = 'deposit:auto-debit';
    protected $description = 'Auto debit deposit untuk tagihan yang belum lunas';

    public function handle()
    {
        // Ambil admin/sistem user untuk transaksi otomatis
        $systemUser = Pengguna::where('role', 'Admin')->first();

        if (!$systemUser) {
            $this->error('❌ Admin user tidak ditemukan!');
            return;
        }

        $tagihan = Tagihan::with(['pelanggan.deposit'])
                         ->whereIn('status', ['Belum Bayar', 'Tunggakan'])
                         ->get();

        $processed = 0;

        foreach ($tagihan as $t) {
            if ($t->pelanggan->deposit && $t->pelanggan->deposit->saldo_deposit > 0) {
                $sisa_tagihan = $t->sisa_tagihan;
                $saldo_deposit = $t->pelanggan->deposit->saldo_deposit;

                // Ambil jumlah minimum dari sisa tagihan dan saldo deposit
                $jumlah_debit = min($sisa_tagihan, $saldo_deposit);

                if ($jumlah_debit > 0) {
                    DB::beginTransaction();
                    try {
                        // Buat transaksi pembayaran
                        TransaksiPembayaran::create([
                            'id_tagihan' => $t->id_tagihan,
                            'id_pengguna' => $systemUser->id_pengguna,
                            'jml_bayar_input' => $jumlah_debit,
                        ]);

                        // Kurangi saldo deposit
                        $t->pelanggan->deposit->kurangiSaldo($jumlah_debit);

                        DB::commit();
                        $processed++;

                        $this->info("✅ Auto debit Rp " . number_format($jumlah_debit, 0, ',', '.') .
                                   " untuk {$t->pelanggan->nama} - Periode {$t->periode}");
                    } catch (\Exception $e) {
                        DB::rollback();
                        $this->error("❌ Error: " . $e->getMessage());
                    }
                }
            }
        }

        $this->info("📝 Total transaksi auto debit: {$processed}");
    }
}