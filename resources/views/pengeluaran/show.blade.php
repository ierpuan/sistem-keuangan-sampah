@extends('layouts.app')

@section('title', 'Detail Pengeluaran')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Detail Pengeluaran</h1>
    <nav class="flex text-sm text-gray-600 mt-2" aria-label="breadcrumb">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('pengeluaran.index') }}" class="hover:text-blue-600">Pengeluaran</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">Detail</span>
    </nav>
</div>

<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-800 text-white px-6 py-6 rounded-t-lg">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">{{ $pengeluaran->judul }}</h2>
                <div class="flex items-center gap-4 text-sm text-gray-200">
                    <span>
                        <i class="far fa-calendar mr-1"></i>
                        {{ \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d F Y') }}
                    </span>
                    <span>
                        <i class="far fa-user mr-1"></i>
                        {{ $pengeluaran->pengguna->name ?? 'Admin' }}
                    </span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-gray-300 text-sm mb-1">Total Pengeluaran</p>
                <p class="text-3xl font-bold">
                    Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}
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
                    <label class="block text-sm font-semibold text-gray-600 mb-1">ID Pengeluaran</label>
                    <p class="text-gray-800">{{ $pengeluaran->id_pengeluaran }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Kategori</label>
                    @if($pengeluaran->kategori)
                        <span class="inline-block bg-green-50 text-green-700 text-sm px-3 py-1 rounded">
                            {{ $pengeluaran->kategori }}
                        </span>
                    @else
                        <p class="text-gray-400">Tidak ada kategori</p>
                    @endif
                </div>
            </div>

            <!-- Info Kanan -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Tanggal Input</label>
                    <p class="text-gray-800">{{ $pengeluaran->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Terakhir Diupdate</label>
                    <p class="text-gray-800">{{ $pengeluaran->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Keterangan -->
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-600 mb-2">Keterangan</label>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                @if($pengeluaran->keterangan)
                    <p class="text-gray-700 whitespace-pre-line">{{ $pengeluaran->keterangan }}</p>
                @else
                    <p class="text-gray-400 italic">Tidak ada keterangan</p>
                @endif
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-200 pt-6">
            <!-- Tombol Aksi -->
            <div class="flex gap-2">
                <a href="{{ route('pengeluaran.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-5 py-2 rounded transition">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('pengeluaran.edit', $pengeluaran->id_pengeluaran) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded transition">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('pengeluaran.destroy', $pengeluaran->id_pengeluaran) }}"
                      method="POST"
                      class="inline"
                      onsubmit="return confirm('Yakin ingin menghapus pengeluaran ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded transition">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection