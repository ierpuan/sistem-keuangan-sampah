<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use App\Models\DepositPelanggan;
use Illuminate\Database\Seeder;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        $pelanggan = [
            [
                'nama' => 'Puskesmas Karangbinangun',
                'dusun' => 'Sambo',
                'rt' => '001',
                'rw' => '001',
                'alamat' => 'Jl. Ratan No. 10',
                'status_aktif' => 'Aktif',
                'latitude' => -7.02492000,
                'longitude' => 112.48130000,
            ],
            [
                'nama' => 'Irfan',
                'dusun' => 'Sambo',
                'rt' => '002',
                'rw' => '003',
                'alamat' => 'gang sarmat',
                'status_aktif' => 'Aktif',
                'latitude' => -7.02218100,
                'longitude' => 112.47971100,
            ],
            [
                'nama' => 'ilman',
                'dusun' => 'Sambo',
                'rt' => '003',
                'rw' => '002',
                'alamat' => 'R. sayyid',
                'status_aktif' => 'Aktif',
                'latitude' => -7.02250000,
                'longitude' => 112.47956700,
            ],
            [
                'nama' => 'Joni',
                'dusun' => 'Sambo',
                'rt' => '001',
                'rw' => '002',
                'alamat' => 'Demang 3',
                'status_aktif' => 'Aktif',
                'latitude' => -7.02149300,
                'longitude' => 112.48051500,
            ],
        ];

        foreach ($pelanggan as $data) {
            $p = Pelanggan::create($data);

            // Buat deposit untuk setiap pelanggan
            DepositPelanggan::create([
                'id_pelanggan' => $p->id_pelanggan,
                'saldo_deposit' => rand(0, 50000),
            ]);
        }
    }
}