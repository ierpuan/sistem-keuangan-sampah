@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Detail Pelanggan</h1>
    <nav class="flex text-xs text-gray-600 mt-2" aria-label="breadcrumb">
        <a href="{{ route('dashboard') }}" class="hover:text-gray-800">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('pelanggan.index') }}" class="hover:text-gray-800">Pelanggan</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">Detail</span>
    </nav>
</div>

<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-800 text-white px-4 py-4 rounded-t-lg">
        <div class="flex flex-col sm:flex-row items-start justify-between gap-4">
            <div class="flex-1">
                <h2 class="text-xl font-bold mb-2">{{ $pelanggan->nama }}</h2>
                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-200">
                    <span>
                        <i class="fas fa-id-card mr-1"></i>
                        ID: {{ $pelanggan->id_pelanggan }}
                    </span>
                    <span class="px-2 py-1 text-xs rounded-full {{ $pelanggan->status_aktif === 'Aktif' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                        {{ $pelanggan->status_aktif }}
                    </span>
                </div>
            </div>
            <div class="text-left sm:text-right w-full sm:w-auto">
                <p class="text-gray-300 text-xs mb-1">Saldo Deposit</p>
                <p class="text-2xl font-bold">
                    Rp {{ number_format($pelanggan->deposit->saldo_deposit ?? 0, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Info Kiri -->
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat Lengkap</label>
                    <p class="text-sm text-gray-800">{{ $pelanggan->alamat_lengkap }}</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Dusun</label>
                    <p class="text-sm text-gray-800">{{ $pelanggan->dusun ?? '-' }}</p>
                </div>

                @if($pelanggan->latitude && $pelanggan->longitude)
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Koordinat GPS</label>
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="text-xs text-gray-800">{{ $pelanggan->latitude }}, {{ $pelanggan->longitude }}</p>
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
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Status Aktif</label>
                    <span class="inline-block px-2 py-1 text-xs rounded-full {{ $pelanggan->status_aktif === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $pelanggan->status_aktif }}
                    </span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Terdaftar Sejak</label>
                    <p class="text-sm text-gray-800">{{ $pelanggan->created_at->format('d F Y') }}</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Terakhir Diupdate</label>
                    <p class="text-sm text-gray-800">{{ $pelanggan->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-200 pt-4">
            <!-- Tombol Aksi -->
            <div class="flex sm:flex-row gap-2">
                <a href="{{ route('pelanggan.index') }}"
                   class="text-center bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded transition text-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
                <a href="{{ route('pelanggan.edit', $pelanggan->id_pelanggan) }}"
                   class="text-center bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded transition text-sm">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
                <a href="{{ route('tagihan.index', ['pelanggan' => $pelanggan->id_pelanggan]) }}"
                   class="text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition text-sm">
                    <i class="fas fa-file-invoice mr-1"></i>Lihat Tagihan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection