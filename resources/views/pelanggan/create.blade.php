@extends('layouts.app')

@section('title', 'Tambah Pelanggan')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Tambah Pelanggan Baru</h1>
    <nav class="flex text-sm text-gray-600 mt-2" aria-label="breadcrumb">
        <a href="{{ route('dashboard') }}" class="hover:text-yellow-600">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('pelanggan.index') }}" class="hover:text-yellow-600">Pelanggan</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">Tambah</span>
    </nav>
</div>

<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-800 text-white px-6 py-4 rounded-t-lg">
        <h2 class="text-xl font-bold">Form Pelanggan Baru</h2>
        <p class="text-sm text-gray-300 mt-1">Lengkapi semua field yang bertanda (*) wajib diisi</p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('pelanggan.store') }}" class="p-6">
        @csrf

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-lg">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                    <span class="font-medium">Terdapat kesalahan pada form:</span>
                </div>
                <ul class="list-disc list-inside text-sm space-y-1 ml-6">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Data Pribadi -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-gray-500">
                <i class="fas fa-user mr-2 text-gary-600"></i>Data Pribadi
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="nama"
                           value="{{ old('nama') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent @error('nama') border-red-500 @enderror"
                           placeholder="Masukkan nama lengkap"
                           required>
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status_aktif"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent"
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
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-gray-500">
                <i class="fas fa-map-marker-alt mr-2 text-gray-600"></i>Alamat Lengkap
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Dusun -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Dusun <span class="text-red-500">*</span>
                    </label>
                    <select name="dusun"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent @error('dusun') border-red-500 @enderror"
                            required>
                        <option value="">-- Pilih Dusun --</option>
                        <option value="Sambo" {{ old('dusun') == 'Sambo' ? 'selected' : '' }}>Sambo</option>
                        <option value="Bulak" {{ old('dusun') == 'Bulak' ? 'selected' : '' }}>Bulak</option>
                    </select>
                    @error('dusun')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- RT -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        RT <span class="text-red-500">*</span>
                    </label>
                    <select name="rt"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent @error('rt') border-red-500 @enderror"
                            required>
                        <option value="">-- Pilih RT --</option>
                        <option value="001" {{ old('rt') == '001' ? 'selected' : '' }}>001</option>
                        <option value="002" {{ old('rt') == '002' ? 'selected' : '' }}>002</option>
                        <option value="003" {{ old('rt') == '003' ? 'selected' : '' }}>003</option>
                    </select>
                    @error('rt')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- RW -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        RW <span class="text-red-500">*</span>
                    </label>
                    <select name="rw"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent @error('rw') border-red-500 @enderror"
                            required>
                        <option value="">-- Pilih RW --</option>
                        <option value="001" {{ old('rw') == '001' ? 'selected' : '' }}>001</option>
                        <option value="002" {{ old('rw') == '002' ? 'selected' : '' }}>002</option>
                        <option value="003" {{ old('rw') == '003' ? 'selected' : '' }}>003</option>
                        <option value="004" {{ old('rw') == '004' ? 'selected' : '' }}>004</option>
                    </select>
                    @error('rw')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="alamat"
                           value="{{ old('alamat') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent @error('alamat') border-red-500 @enderror"
                           placeholder="Contoh: Jl. Mawar No. 123"
                           required>
                    @error('alamat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Koordinat GPS -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-gray-500">
                <i class="fas fa-map-marked-alt mr-2 text-gray-600"></i>Koordinat GPS
            </h3>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                <div class="flex items-start gap-2 text-sm text-gray-800">
                    <i class="fas fa-info-circle text-gray-600 mt-0.5"></i>
                    <div>
                        <p class="font-medium">Petunjuk Koordinat:</p>
                        <ul class="list-disc list-inside mt-1 text-gray-700 space-y-1">
                            <li>Gunakan format desimal (contoh: -7.5678901)</li>
                            <li>Latitude untuk koordinat Utara-Selatan</li>
                            <li>Longitude untuk koordinat Timur-Barat</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Latitude -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Latitude <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           step="0.00000001"
                           name="latitude"
                           value="{{ old('latitude') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent @error('latitude') border-red-500 @enderror"
                           placeholder="Contoh: -7.5678901"
                           required>
                    @error('latitude')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Longitude -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Longitude <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           step="0.00000001"
                           name="longitude"
                           value="{{ old('longitude') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent @error('longitude') border-red-500 @enderror"
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
                    class="bg-gray-600 hover:bg-gray-800 text-white px-6 py-2.5 rounded-lg transition font-medium">
                <i class="fas fa-save mr-2"></i>Simpan Pelanggan
            </button>
            <a href="{{ route('pelanggan.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2.5 rounded-lg transition font-medium">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
        </div>
    </form>
</div>
@endsection