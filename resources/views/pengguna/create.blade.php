@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="mb-3">
    <h1 class="text-2xl font-bold text-gray-800">Tambah Pengguna Baru</h1>
    <p class="text-sm text-gray-600">Buat akun pengguna baru</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <!-- Header Form -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
        <h2 class="text-lg font-semibold text-white">
            <i class="fas fa-user-plus mr-2"></i>Form Pengguna
        </h2>
    </div>

    <!-- Form Content -->
    <form method="POST" action="{{ route('pengguna.store') }}" class="p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Nama -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-0 focus:border-gray-500 @error('nama') border-red-500 @enderror"
                    required>
                @error('nama')
                    <p class="text-red-500 text-xs mt-1">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Username -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Username <span class="text-red-500">*</span>
                </label>
                <input type="text" name="username" value="{{ old('username') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-0 focus:border-gray-500 @error('username') border-red-500 @enderror"
                    required>
                @error('username')
                    <p class="text-red-500 text-xs mt-1">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 text-sm focus:outline-none focus:ring-0 focus:border-gray-500 @error('password') border-red-500 @enderror"
                        required>
                    <button type="button"
                        onclick="togglePassword('password', this)"
                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Konfirmasi Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 text-sm focus:outline-none focus:ring-0 focus:border-gray-500"
                        required>
                    <button type="button"
                        onclick="togglePassword('password_confirmation', this)"
                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Role -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Role <span class="text-red-500">*</span>
                </label>

                <input type="hidden" name="role" id="roleValue"
                       value="{{ old('role') }}">

                <div class="relative">
                    <button type="button"
                        onclick="document.getElementById('roleDropdown').classList.toggle('hidden')"
                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2 text-sm flex justify-between items-center hover:bg-gray-50 focus:outline-none focus:ring-0 focus:border-gray-500 @error('role') border-red-500 @enderror">
                        <span id="roleText">
                            {{ old('role', '-- Pilih Role --') }}
                        </span>
                        <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                    </button>

                    <div id="roleDropdown"
                        class="hidden absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-md">
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

                @error('role')
                    <p class="text-red-500 text-xs mt-1">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        <!-- Info Role -->
        <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
            <p class="text-sm text-blue-800">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Perbedaan Role:</strong>
            </p>
            <ul class="text-sm text-blue-700 mt-2 ml-6 list-disc">
                <li><strong>Admin:</strong> Akses penuh sistem</li>
                <li><strong>Petugas:</strong> Operasional harian</li>
            </ul>
        </div>

        <!-- Tombol Action -->
        <div class="mt-6 pt-6 border-t border-gray-200 flex sm:flex-row gap-3">
            <button type="submit"
                class="bg-gray-700 hover:bg-gray-800 text-white px-6 py-2.5 rounded-lg transition text-sm font-medium">
                <i class="fas fa-save mr-2"></i>Simpan
            </button>
            <a href="{{ route('pengguna.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2.5 rounded-lg transition text-sm font-medium text-center">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

    </form>
</div>

{{-- SCRIPT --}}
<script>
    function setRole(role) {
        document.getElementById('roleText').innerText = role;
        document.getElementById('roleValue').value = role;
        document.getElementById('roleDropdown').classList.add('hidden');
    }

    document.addEventListener('click', function (e) {
        const dropdown = document.getElementById('roleDropdown');
        const button = dropdown.previousElementSibling;

        if (!button.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection