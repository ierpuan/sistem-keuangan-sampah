@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Tambah Pengguna Baru</h1>
    <p class="text-gray-600">Buat akun pengguna baru</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('pengguna.store') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                       class="w-full border rounded-lg px-4 py-2 @error('nama') border-red-500 @enderror"
                       placeholder="Masukkan nama lengkap" required autofocus>
                @error('nama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Username <span class="text-red-500">*</span>
                </label>
                <input type="text" name="username" value="{{ old('username') }}"
                       class="w-full border rounded-lg px-4 py-2 @error('username') border-red-500 @enderror"
                       placeholder="Masukkan username" required>
                @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Username harus unik</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Password <span class="text-red-500">*</span>
                </label>
                <input type="password" name="password"
                       class="w-full border rounded-lg px-4 py-2 @error('password') border-red-500 @enderror"
                       placeholder="Minimal 6 karakter" required>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Konfirmasi Password <span class="text-red-500">*</span>
                </label>
                <input type="password" name="password_confirmation"
                       class="w-full border rounded-lg px-4 py-2"
                       placeholder="Ulangi password" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Role <span class="text-red-500">*</span>
                </label>
                <select name="role" class="w-full border rounded-lg px-4 py-2 @error('role') border-red-500 @enderror" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="Admin" {{ old('role') === 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Petugas" {{ old('role') === 'Petugas' ? 'selected' : '' }}>Petugas</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-sm text-blue-800">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Perbedaan Role:</strong>
            </p>
            <ul class="text-sm text-blue-700 mt-2 ml-6 list-disc">
                <li><strong>Admin:</strong> Akses penuh ke semua fitur termasuk kelola pengguna</li>
                <li><strong>Petugas:</strong> Akses terbatas untuk operasional harian (tagihan, pembayaran, pengeluaran)</li>
            </ul>
        </div>

        <div class="mt-6 flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-save mr-2"></i>Simpan
            </button>
            <a href="{{ route('pengguna.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
        </div>
    </form>
</div>
@endsection