@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Tambah Pengguna Baru</h1>
    <p class="text-sm text-gray-600">Buat akun pengguna baru</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('pengguna.store') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Nama -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2
                           focus:outline-none focus:ring-0
                           @error('nama') border-red-500 @enderror"
                    required>
                @error('nama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Username -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Username <span class="text-red-500">*</span>
                </label>
                <input type="text" name="username" value="{{ old('username') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2
                           focus:outline-none focus:ring-0
                           @error('username') border-red-500 @enderror"
                    required>
                @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Password <span class="text-red-500">*</span>
                </label>
                <input type="password" name="password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2
                           focus:outline-none focus:ring-0
                           @error('password') border-red-500 @enderror"
                    required>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Konfirmasi Password <span class="text-red-500">*</span>
                </label>
                <input type="password" name="password_confirmation"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2
                           focus:outline-none focus:ring-0"
                    required>
            </div>

            <!-- ROLE (STYLE CUSTOM, NO TOGGLE) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Role <span class="text-red-500">*</span>
                </label>

                <input type="hidden" name="role" id="roleValue"
                       value="{{ old('role', $pengguna->role ?? '') }}">

                <div class="relative">
                    <button type="button"
                        onclick="document.getElementById('roleDropdown').classList.toggle('hidden')"
                        class="w-full bg-white border rounded-lg px-4 py-2 text-sm
                               flex justify-between items-center hover:bg-gray-50
                               @error('role') border-red-500 @enderror">

                        <span id="roleText">
                            {{ old('role', $pengguna->role ?? '') }}
                        </span>

                        <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                    </button>

                    <div id="roleDropdown"
                        class="hidden absolute z-50 mt-1 w-full bg-white border rounded-lg shadow-md">

                        <div onclick="setRole('Admin')"
                            class="px-4 py-2 text-sm hover:bg-gray-100 cursor-pointer">
                            Admin
                        </div>

                        <div onclick="setRole('Petugas')"
                            class="px-4 py-2 text-sm hover:bg-gray-100 cursor-pointer">
                            Petugas
                        </div>
                    </div>
                </div>

        </div>


        <!-- Info Role -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-sm text-blue-800">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Perbedaan Role:</strong>
            </p>
            <ul class="text-sm text-blue-700 mt-2 ml-6 list-disc">
                <li><strong>Admin:</strong> Akses penuh sistem</li>
                <li><strong>Petugas:</strong> Operasional harian</li>
            </ul>
        </div>

        <!-- Tombol -->
        <div class="mt-6 flex gap-4">
            <button type="submit"
                class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-save mr-2"></i>Simpan
            </button>

            <a href="{{ route('pengguna.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
        </div>

    </form>
</div>
<script>
    function setRole(role) {
        // set text yang tampil
        document.getElementById('roleText').innerText = role;

        // set value ke input hidden
        document.getElementById('roleValue').value = role;

        // tutup dropdown
        document.getElementById('roleDropdown').classList.add('hidden');
    }

    // tutup dropdown jika klik di luar
    document.addEventListener('click', function (e) {
        const dropdown = document.getElementById('roleDropdown');
        const button = dropdown.previousElementSibling;

        if (!button.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>
@endsection
