@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')

<!-- Header -->
<div class="mb-3">
    <h1 class="text-2xl font-bold text-gray-800">Detail Pengguna</h1>
    <p class="text-sm text-gray-600">Informasi lengkap akun pengguna</p>
</div>

<!-- Tombol -->
<div class="flex flex-wrap gap-2 mb-4">
    <a href="{{ route('pengguna.edit', $pengguna->id_pengguna) }}"
       class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded text-sm">
        <i class="fas fa-edit mr-1"></i>Edit
    </a>

    <button onclick="document.getElementById('modalResetPassword').classList.remove('hidden')"
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">
        <i class="fas fa-key mr-1"></i>Reset Password
    </button>

    <a href="{{ route('pengguna.index') }}"
       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded text-sm">
        <i class="fas fa-arrow-left mr-1"></i>Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    <!-- Profil -->
    <div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center gap-4 mb-4">
                <div class="h-14 w-14 rounded-full bg-gray-700 flex items-center justify-center
                            text-white text-xl font-bold">
                    {{ strtoupper(substr($pengguna->nama, 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-gray-800">{{ $pengguna->nama }}</p>
                    <p class="text-sm text-gray-600">{{ $pengguna->username }}</p>
                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-xs
                        {{ $pengguna->role === 'Admin'
                            ? 'bg-gray-800 text-white'
                            : 'bg-gray-200 text-gray-800' }}">
                        {{ $pengguna->role }}
                    </span>
                </div>
            </div>

            <div class="text-sm text-gray-600 space-y-2 border-t pt-3">
                <div class="flex justify-between">
                    <span>Terdaftar</span>
                    <span class="font-medium">{{ $pengguna->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Bergabung</span>
                    <span class="font-medium">{{ $pengguna->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="bg-white rounded-lg shadow p-4 mt-4">
            <h3 class="font-semibold text-gray-800 mb-3 text-sm">
                Statistik Aktivitas
            </h3>

            <div class="space-y-3">
                <div class="border rounded p-3">
                    <p class="text-xs text-gray-600">Total Transaksi Pembayaran</p>
                    <p class="text-lg font-bold text-gray-800">
                        {{ $stats['total_transaksi'] }}
                    </p>
                    <p class="text-xs text-gray-500">
                        Rp {{ number_format($stats['total_pembayaran'], 0, ',', '.') }}
                    </p>
                </div>

                <div class="border rounded p-3">
                    <p class="text-xs text-gray-600">Pengeluaran Dicatat</p>
                    <p class="text-lg font-bold text-gray-800">
                        {{ $stats['total_pengeluaran_dicatat'] }}
                    </p>
                    <p class="text-xs text-gray-500">
                        Rp {{ number_format($stats['total_nilai_pengeluaran'], 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Aktivitas -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-4 py-3 border-b">
                <h3 class="font-semibold text-gray-800 text-sm">
                    Transaksi Pembayaran Terbaru
                </h3>
            </div>

            @if($transaksi_terbaru->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs text-gray-500 uppercase">Tanggal</th>
                                <th class="px-3 py-3 text-left text-xs text-gray-500 uppercase">Pelanggan</th>
                                <th class="px-3 py-3 text-left text-xs text-gray-500 uppercase">Periode</th>
                                <th class="px-3 py-3 text-right text-xs text-gray-500 uppercase">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($transaksi_terbaru as $t)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 text-sm">
                                        {{ $t->tgl_bayar->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-3 py-2 text-sm">
                                        {{ $t->tagihan->pelanggan->nama }}
                                    </td>
                                    <td class="px-3 py-2 text-sm">
                                        {{ $t->tagihan->periode }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-right font-bold">
                                        Rp {{ number_format($t->jml_bayar_input, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-8 text-center text-sm text-gray-500">
                    <i class="fas fa-receipt text-4xl mb-2 text-gray-300"></i>
                    <p>Belum ada transaksi</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Reset Password -->
<div id="modalResetPassword"
     class="hidden fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow w-full max-w-sm p-4">
        <h3 class="font-semibold text-gray-800 mb-3">Reset Password</h3>

        <form method="POST"
              action="{{ route('pengguna.reset-password', $pengguna->id_pengguna) }}">
            @csrf

            <div class="mb-3">
                <label class="text-xs text-gray-600">Password Baru</label>
                <input type="password" name="new_password"
                       class="w-full border rounded px-3 py-2 text-sm" required>
            </div>

            <div class="mb-3">
                <label class="text-xs text-gray-600">Konfirmasi Password</label>
                <input type="password" name="new_password_confirmation"
                       class="w-full border rounded px-3 py-2 text-sm" required>
            </div>

            <div class="flex gap-2">
                <button class="flex-1 bg-gray-700 hover:bg-gray-800 text-white px-3 py-2 rounded text-sm">
                    Reset
                </button>
                <button type="button"
                        onclick="document.getElementById('modalResetPassword').classList.add('hidden')"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-2 rounded text-sm">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
