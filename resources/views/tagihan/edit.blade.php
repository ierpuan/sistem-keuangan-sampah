@extends('layouts.app')

@section('title', 'Edit Tagihan')

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Edit Tagihan</h1>
    <nav class="flex text-xs text-gray-600 mt-2" aria-label="breadcrumb">
        <a href="{{ route('dashboard') }}" class="hover:text-gray-800">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('tagihan.index') }}" class="hover:text-gray-800">Tagihan</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">Edit</span>
    </nav>
</div>

<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-800 text-white px-4 py-4 rounded-t-lg">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold mb-1">Edit Data Tagihan</h2>
                <p class="text-xs text-gray-200">Update informasi tagihan pelanggan</p>
            </div>
            <div class="hidden sm:block">
                <i class="fas fa-edit text-4xl text-gray-300 opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="p-4">
        <!-- Info Box -->
        <div class="mb-4 bg-blue-50 border-l-4 border-blue-500 p-3 rounded">
            <div class="flex items-start gap-2">
                <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                <div class="text-xs text-blue-800">
                    <strong class="font-semibold">Perhatian:</strong> Anda hanya dapat mengedit jumlah tagihan untuk tagihan yang belum memiliki riwayat pembayaran.
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('tagihan.update', $tagihan->id_tagihan) }}">
            @csrf
            @method('PUT')

            <!-- Info Pelanggan (Read-only) -->
            <div class="mb-4 bg-gray-50 rounded-lg p-3 border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                    <i class="fas fa-user text-gray-500 mr-2"></i>
                    Informasi Pelanggan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Pelanggan</label>
                        <input type="text"
                               value="{{ $tagihan->pelanggan->nama }}"
                               class="w-full border border-gray-300 rounded px-3 py-2 bg-white text-sm text-gray-700 cursor-not-allowed"
                               readonly>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Periode Tagihan</label>
                        <input type="text"
                               value="{{ \Carbon\Carbon::parse($tagihan->periode . '-01')->translatedFormat('F Y') }}"
                               class="w-full border border-gray-300 rounded px-3 py-2 bg-white text-sm text-gray-700 cursor-not-allowed"
                               readonly>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Jatuh Tempo</label>
                        <input type="text"
                               value="{{ $tagihan->jatuh_tempo->format('d/m/Y') }}"
                               class="w-full border border-gray-300 rounded px-3 py-2 bg-white text-sm text-gray-700 cursor-not-allowed"
                               readonly>
                    </div>
                </div>
            </div>

            <!-- Form Edit -->
            <div class="mb-4">
                <!-- Jumlah Tagihan -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Jumlah Tagihan Pokok <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                        <input type="number"
                               name="jml_tagihan_pokok"
                               value="{{ old('jml_tagihan_pokok', number_format($tagihan->jml_tagihan_pokok, 0, '', '')) }}"
                               class="w-full border rounded px-3 py-2 pl-10 text-sm @error('jml_tagihan_pokok') border-red-500 @else border-gray-300 @enderror focus:ring-0 focus:border-gray-300"
                               min="0"
                               step="1"
                               placeholder="contoh: 50000"
                               required>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Input nominal tanpa tanda titik (.)</p>
                    @error('jml_tagihan_pokok')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="border-t border-gray-200 pt-4">
                <div class="flex flex-wrap gap-2">
                    <button type="submit"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm transition flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                    <a href="{{ route('tagihan.index') }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded text-sm transition flex items-center gap-2">
                        <i class="fas fa-times"></i>
                        <span>Batal</span>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection