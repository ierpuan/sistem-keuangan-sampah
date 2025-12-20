@extends('layouts.app')

@section('title', 'Tambah Pengeluaran')

@section('content')
<div class="mb-3">
    <h1 class="text-2xl font-bold text-gray-800">Tambah Pengeluaran</h1>
    <p class="text-sm text-gray-600">Catat pengeluaran operasional</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <!-- Header Form -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
        <h2 class="text-lg font-semibold text-white">
            <i class="fas fa-file-invoice-dollar mr-2"></i>Form Pengeluaran
        </h2>
    </div>

    <!-- Form Content -->
    <form method="POST" action="{{ route('pengeluaran.store') }}" class="p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tanggal -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal <span class="text-red-500">*</span>
                </label>
                <input type="date"
                       name="tanggal_pengeluaran"
                       value="{{ old('tanggal_pengeluaran', date('Y-m-d')) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent @error('tanggal_pengeluaran') border-red-500 @enderror"
                       required>
                @error('tanggal_pengeluaran')
                    <p class="text-red-500 text-xs mt-1">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Kategori <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="kategori"
                       value="{{ old('kategori') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent @error('kategori') border-red-500 @enderror"
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
                           value="{{ old('jumlah') }}"
                           class="w-full border border-gray-300 rounded-lg pl-12 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent @error('jumlah') border-red-500 @enderror"
                           min="0"
                           step="1000"
                           placeholder="0"
                           required>
                </div>
                @error('jumlah')
                    <p class="text-red-500 text-xs mt-1">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>Masukkan jumlah tanpa tanda titik (.) atau koma (,)
                </p>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Keterangan
                </label>
                <textarea name="keterangan"
                          rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent @error('keterangan') border-red-500 @enderror"
                          placeholder="Detail pengeluaran (opsional)...">{{ old('keterangan') }}</textarea>
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
                <i class="fas fa-save mr-2"></i>Simpan Pengeluaran
            </button>
            <a href="{{ route('pengeluaran.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2.5 rounded-lg transition text-sm font-medium text-center">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </form>
</div>

<!-- Info Card -->
{{-- <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-500 text-xl"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Tips Pengisian</h3>
            <div class="mt-2 text-sm text-blue-700">
                <ul class="list-disc list-inside space-y-1">
                    <li>Pastikan tanggal pengeluaran sudah benar</li>
                    <li>Gunakan nama kategori yang konsisten untuk memudahkan laporan</li>
                    <li>Isi keterangan dengan detail untuk dokumentasi yang lebih baik</li>
                </ul>
            </div>
        </div>
    </div>
</div> --}}
@endsection