<?php

namespace Database\Seeders;

use App\Models\Tagihan;
use App\Models\Pelanggan;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TagihanSeeder extends Seeder
{
    public function run(): void
    {
        $pelanggan = Pelanggan::where('status_aktif', 'Aktif')->get();
        $periodeList = ['2024-09', '2025-09'];

        foreach ($pelanggan as $p) {
            foreach ($periodeList as $periode) {
                Tagihan::create([
                    'id_pelanggan' => $p->id_pelanggan,
                    'periode' => $periode,
                    'jml_tagihan_pokok' => 10000,
                    'jatuh_tempo' => Carbon::createFromFormat('Y-m', $periode)->endOfMonth(),
                    'total_sudah_bayar' => $periode === '2025-09' ? 10000 : 0,
                    'status' => $periode === '2025-09' ? 'Lunas' : 'Belum Bayar',
                ]);
            }
        }
    }
}