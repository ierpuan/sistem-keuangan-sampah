@extends('layouts.app')

@section('title', 'Detail Pengeluaran')

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Detail Pengeluaran</h1>
    <nav class="flex text-xs text-gray-600 mt-2" aria-label="breadcrumb">
        <a href="{{ route('dashboard') }}" class="hover:text-gray-800">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('pengeluaran.index') }}" class="hover:text-gray-800">Pengeluaran</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">Detail</span>
    </nav>
</div>

<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-800 text-white px-4 py-4 rounded-t-lg">
        <div class="flex flex-col sm:flex-row items-start justify-between gap-4">
            <div class="flex-1">
                <h2 class="text-xl font-bold mb-2">{{ $pengeluaran->judul }}</h2>
                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-200">
                    <span>
                        <i class="far fa-calendar mr-1"></i>
                        {{ \Carbon\Carbon::parse($pengeluaran->tanggal)->translatedFormat('d F Y') }}
                    </span>
                    <span>
                        <i class="far fa-user mr-1"></i>
                        {{ $pengeluaran->pengguna->name ?? 'Admin' }}
                    </span>
                </div>
            </div>
            <div class="text-left sm:text-right w-full sm:w-auto">
                <p class="text-gray-300 text-xs mb-1">Total Pengeluaran</p>
                <p class="text-2xl font-bold">
                    Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}
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
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        ID Pengeluaran
                    </label>
                    <p class="text-sm text-gray-800">
                        {{ $pengeluaran->id_pengeluaran }}
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Jenis Pengeluaran
                    </label>
                    @if($pengeluaran->kategori)
                        <span class="inline-block px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                            {{ $pengeluaran->kategori }}
                        </span>
                    @else
                        <p class="text-sm text-gray-400">-</p>
                    @endif
                </div>
            </div>

            <!-- Info Kanan -->
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Tanggal Input
                    </label>
                    <p class="text-sm text-gray-800">
                        {{ $pengeluaran->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Terakhir Diupdate
                    </label>
                    <p class="text-sm text-gray-800">
                        {{ $pengeluaran->updated_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Keterangan -->
        <div class="mb-4">
            <label class="block text-xs font-semibold text-gray-600 mb-1">
                Keterangan
            </label>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                @if($pengeluaran->keterangan)
                    <p class="text-sm text-gray-700 whitespace-pre-line">
                        {{ $pengeluaran->keterangan }}
                    </p>
                @else
                    <p class="text-sm text-gray-400 italic">
                        Tidak ada keterangan
                    </p>
                @endif
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-200 pt-4">
            <!-- Tombol Aksi -->
            <div class="flex sm:flex-row gap-2">
                <a href="{{ route('pengeluaran.index') }}"
                   class="text-center bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded transition text-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>

                <a href="{{ route('pengeluaran.edit', $pengeluaran->id_pengeluaran) }}"
                   class="text-center bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded transition text-sm">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>

                <form action="{{ route('pengeluaran.destroy', $pengeluaran->id_pengeluaran) }}"
                      method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus pengeluaran ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition text-sm">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
