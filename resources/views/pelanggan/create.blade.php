@extends('layouts.app')

@section('title', 'Tambah Pelanggan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Tambah Pelanggan</h1>
    <nav class="flex text-xs text-gray-600 mt-2">
        <a href="{{ route('dashboard') }}" class="hover:text-yellow-600">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('pelanggan.index') }}" class="hover:text-yellow-600">Pelanggan</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">Tambah</span>
    </nav>
</div>

<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-800 text-white px-4 py-3 rounded-t-lg">
        <h2 class="text-lg font-bold">Form Pelanggan Baru</h2>
        <p class="text-xs text-gray-300 mt-1">Lengkapi semua field yang bertanda (*) wajib diisi</p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('pelanggan.store') }}" class="p-4">
        @csrf

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-800 p-3 rounded-lg">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                    <span class="font-medium text-sm">Terdapat kesalahan pada form:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 ml-6">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Data Pribadi -->
        <div class="mb-6">
            <h3 class="text-base font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-500">
                Data Pribadi
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nama -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="nama"
                           value="{{ old('nama') }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-transparent @error('nama') border-red-500 @enderror"
                           placeholder="Masukkan nama lengkap"
                           required>
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status_aktif"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-transparent"
                            required>
                        <option value="Aktif" {{ old('status_aktif') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Nonaktif" {{ old('status_aktif') === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status_aktif')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Alamat -->
        <div class="mb-6">
            <h3 class="text-base font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-500">
                Alamat Lengkap
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Dusun -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Dusun <span class="text-red-500">*</span>
                    </label>
                    <select name="dusun"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-transparent @error('dusun') border-red-500 @enderror"
                            required>
                        <option value="">-- Pilih Dusun --</option>
                        <option value="Sambo" {{ old('dusun') === 'Sambo' ? 'selected' : '' }}>Sambo</option>
                        <option value="Bulak" {{ old('dusun') === 'Bulak' ? 'selected' : '' }}>Bulak</option>
                    </select>
                    @error('dusun')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- RT -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        RT <span class="text-red-500">*</span>
                    </label>
                    <select name="rt"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-transparent @error('rt') border-red-500 @enderror"
                            required>
                        <option value="">-- Pilih RT --</option>
                        <option value="001" {{ old('rt') === '001' ? 'selected' : '' }}>001</option>
                        <option value="002" {{ old('rt') === '002' ? 'selected' : '' }}>002</option>
                        <option value="003" {{ old('rt') === '003' ? 'selected' : '' }}>003</option>
                    </select>
                    @error('rt')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- RW -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        RW <span class="text-red-500">*</span>
                    </label>
                    <select name="rw"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-transparent @error('rw') border-red-500 @enderror"
                            required>
                        <option value="">-- Pilih RW --</option>
                        <option value="001" {{ old('rw') === '001' ? 'selected' : '' }}>001</option>
                        <option value="002" {{ old('rw') === '002' ? 'selected' : '' }}>002</option>
                        <option value="003" {{ old('rw') === '003' ? 'selected' : '' }}>003</option>
                        <option value="004" {{ old('rw') === '004' ? 'selected' : '' }}>004</option>
                    </select>
                    @error('rw')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Alamat <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="alamat"
                           value="{{ old('alamat') }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-transparent @error('alamat') border-red-500 @enderror"
                           placeholder="Contoh: Jl. Mawar No. 123"
                           required>
                    @error('alamat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Koordinat GPS -->
        <div class="mb-6">
            <h3 class="text-base font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-500">
                Koordinat GPS
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Latitude -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Latitude <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           step="0.00000001"
                           name="latitude"
                           value="{{ old('latitude') }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-transparent @error('latitude') border-red-500 @enderror"
                           placeholder="Contoh: -7.5678901"
                           required>
                    @error('latitude')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Longitude -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Longitude <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           step="0.00000001"
                           name="longitude"
                           value="{{ old('longitude') }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-transparent @error('longitude') border-red-500 @enderror"
                           placeholder="Contoh: 110.8234567"
                           required>
                    @error('longitude')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex gap-3 pt-6 border-t border-gray-200">
            <button type="submit"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition text-sm">
                <i class="fas fa-save mr-2"></i>Simpan Pelanggan
            </button>
            <a href="{{ route('pelanggan.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition font-medium text-sm">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
        </div>
    </form>
</div>
@endsection
