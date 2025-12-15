<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use Carbon\Carbon;

class GenerateTagihanBulanan extends Command
{
    protected $signature = 'tagihan:generate {periode?}';
    protected $description = 'Generate tagihan bulanan untuk semua pelanggan aktif';

    public function handle()
    {
        $periode = $this->argument('periode') ?? Carbon::now()->format('Y-m');
        $jatuh_tempo = Carbon::createFromFormat('Y-m', $periode)->endOfMonth();

        $pelanggan = Pelanggan::where('status_aktif', 'Aktif')->get();
        $created = 0;
        $skipped = 0;

        foreach ($pelanggan as $p) {
            // Cek apakah sudah ada tagihan untuk periode ini
            $exists = Tagihan::where('id_pelanggan', $p->id_pelanggan)
                            ->where('periode', $periode)
                            ->exists();

            if (!$exists) {
                Tagihan::create([
                    'id_pelanggan' => $p->id_pelanggan,
                    'periode' => $periode,
                    'jml_tagihan_pokok' => 50000, // Default 50rb, bisa disesuaikan
                    'jatuh_tempo' => $jatuh_tempo,
                ]);
                $created++;
            } else {
                $skipped++;
            }
        }

        $this->info("✅ Tagihan berhasil digenerate untuk periode {$periode}");
        $this->info("📝 Dibuat: {$created} tagihan");
        $this->info("⏭️  Dilewati: {$skipped} tagihan (sudah ada)");
    }
}