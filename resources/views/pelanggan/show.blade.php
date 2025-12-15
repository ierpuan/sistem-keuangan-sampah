@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Detail Pelanggan</h1>
    <nav class="flex text-sm text-gray-600 mt-2" aria-label="breadcrumb">
        <a href="{{ route('dashboard') }}" class="hover:text-gray-600">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('pelanggan.index') }}" class="hover:text-gray-600">Pelanggan</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">Detail</span>
    </nav>
</div>

<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-800 text-white px-6 py-6 rounded-t-lg">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">{{ $pelanggan->nama }}</h2>
                <div class="flex items-center gap-4 text-sm text-gray-200">
                    <span>
                        <i class="fas fa-id-card mr-1"></i>
                        ID: {{ $pelanggan->id_pelanggan }}
                    </span>
                    <span class="px-3 py-1 text-xs rounded-full {{ $pelanggan->status_aktif === 'Aktif' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                        {{ $pelanggan->status_aktif }}
                    </span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-gray-300 text-sm mb-1">Saldo Deposit</p>
                <p class="text-3xl font-bold">
                    Rp {{ number_format($pelanggan->deposit->saldo_deposit ?? 0, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Info Kiri -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Alamat Lengkap</label>
                    <p class="text-gray-800">{{ $pelanggan->alamat_lengkap }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Dusun</label>
                    <p class="text-gray-800">{{ $pelanggan->dusun ?? '-' }}</p>
                </div>

                @if($pelanggan->latitude && $pelanggan->longitude)
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Koordinat GPS</label>
                    <div class="flex items-center gap-2">
                        <p class="text-gray-800 text-sm">{{ $pelanggan->latitude }}, {{ $pelanggan->longitude }}</p>
                        <a href="https://www.google.com/maps?q={{ $pelanggan->latitude }},{{ $pelanggan->longitude }}"
                           target="_blank"
                           class="text-yellow-600 hover:text-yellow-700 text-xs">
                            <i class="fas fa-map-marker-alt"></i> Lihat Peta
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Info Kanan -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Status Aktif</label>
                    <span class="inline-block px-3 py-1 text-sm rounded-full {{ $pelanggan->status_aktif === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $pelanggan->status_aktif }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Terdaftar Sejak</label>
                    <p class="text-gray-800">{{ $pelanggan->created_at->format('d F Y') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Terakhir Diupdate</label>
                    <p class="text-gray-800">{{ $pelanggan->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-200 pt-6">
            <!-- Tombol Aksi -->
            <div class="flex gap-2">
                <a href="{{ route('pelanggan.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-5 py-2 rounded transition">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('pelanggan.edit', $pelanggan->id_pelanggan) }}"
                   class="bg-yellow-600 hover:bg-yellow-700 text-white px-5 py-2 rounded transition">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('tagihan.index', ['pelanggan' => $pelanggan->id_pelanggan]) }}"
                   class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded transition">
                    <i class="fas fa-file-invoice"></i> Lihat Tagihan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection