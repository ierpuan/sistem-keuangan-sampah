<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        Pengguna::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'Admin',
        ]);

        Pengguna::create([
            'nama' => 'Petugas 1',
            'username' => 'petugas1',
            'password' => Hash::make('petugas123'),
            'role' => 'Petugas',
        ]);

    }
}