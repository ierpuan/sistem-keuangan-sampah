@extends('layouts.app')

@section('title', 'Edit Pengeluaran')

@section('content')
<div class="mb-3">
    <h1 class="text-2xl font-bold text-gray-800">Edit Pengeluaran</h1>
    <p class="text-sm text-gray-600">Ubah data pengeluaran operasional</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <!-- Header Form -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
        <h2 class="text-lg font-semibold text-white">
            <i class="fas fa-edit mr-2"></i>Form Edit Pengeluaran
        </h2>
    </div>

    <!-- Form Content -->
    <form method="POST" action="{{ route('pengeluaran.update', $pengeluaran->id_pengeluaran) }}" class="p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tanggal -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal <span class="text-red-500">*</span>
                </label>
                <input type="date"
                       name="tanggal_pengeluaran"
                       value="{{ old('tanggal_pengeluaran', $pengeluaran->tanggal_pengeluaran->format('Y-m-d')) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-0 focus:border-gray-500 @error('tanggal_pengeluaran') border-red-500 @enderror"
                       required>
                @error('tanggal_pengeluaran')
                    <p class="text-red-500 text-xs mt-1">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>Format: bulan/hari/tahun
                </p>
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Jenis Pengeluaran <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="kategori"
                       value="{{ old('kategori', $pengeluaran->kategori) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-0 focus:border-gray-500 @error('kategori') border-red-500 @enderror"
                       placeholder="Contoh: Listrik, Maintenance, Gaji"
                       required>
                @error('kategori')
                    <p class="text-red-500 text-xs mt-1">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Jumlah -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Jumlah (Rp) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                    <input type="number"
                           name="jumlah"
                           value="{{ old('jumlah', number_format($pengeluaran->jumlah ?? 0, 0, '', '')) }}"
                           class="w-full border border-gray-300 rounded-lg pl-12 pr-4 py-2 text-sm focus:outline-none focus:ring-0 focus:border-gray-500 @error('jumlah') border-red-500 @enderror"
                           min="0"
                           step="1"
                           placeholder="10000"
                           required>
                </div>
                @error('jumlah')
                    <p class="text-red-500 text-xs mt-1">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>Masukkan jumlah tanpa tanda titik (.)
                </p>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Keterangan
                </label>
                <textarea name="keterangan"
                          rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-0 focus:border-gray-500 @error('keterangan') border-red-500 @enderror"
                          placeholder="Detail pengeluaran (opsional)...">{{ old('keterangan', $pengeluaran->keterangan) }}</textarea>
                @error('keterangan')
                    <p class="text-red-500 text-xs mt-1">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <!-- Tombol Action -->
        <div class="mt-6 pt-6 border-t border-gray-200 flex sm:flex-row gap-3">
            <button type="submit"
                    class="bg-gray-700 hover:bg-gray-800 text-white px-6 py-2.5 rounded-lg transition text-sm font-medium">
                <i class="fas fa-save mr-2"></i>Update Pengeluaran
            </button>
            <a href="{{ route('pengeluaran.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2.5 rounded-lg transition text-sm font-medium text-center">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </form>
</div>
@endsection