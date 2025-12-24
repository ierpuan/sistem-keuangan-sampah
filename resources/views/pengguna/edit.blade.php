@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')

<!-- Header -->
<div class="mb-3">
    <h1 class="text-2xl font-bold text-gray-800">Edit Pengguna</h1>
    <p class="text-sm text-gray-600">Perbarui data akun pengguna</p>
</div>

<!-- Form Card -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <!-- Header Form -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
        <h2 class="text-lg font-semibold text-white">
            <i class="fas fa-user-edit mr-2"></i>Form Edit Pengguna
        </h2>
    </div>

    <!-- Form Content -->
    <form method="POST" action="{{ route('pengguna.update', $pengguna->id_pengguna) }}" class="p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Nama -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="nama"
                       value="{{ old('nama', $pengguna->nama) }}"
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
                <input type="text"
                       name="username"
                       value="{{ old('username', $pengguna->username) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-0 focus:border-gray-500 @error('username') border-red-500 @enderror"
                       required>
                @error('username')
                    <p class="text-red-500 text-xs mt-1">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password Lama -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Password Lama
                </label>
                <input type="password"
                       name="current_password"
                       id="currentPassword"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-0 focus:border-gray-500 @error('current_password') border-red-500 @enderror"
                       placeholder="Isi jika ingin ganti password">
                @error('current_password')
                    <p class="text-red-500 text-xs mt-1">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>Wajib diisi jika ingin mengganti password
                </p>
            </div>

            <!-- Password Baru -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Password Baru
                </label>

                <div class="relative">
                    <input type="password"
                           name="password"
                           id="newPassword"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 text-sm focus:outline-none focus:ring-0 focus:border-gray-500 @error('password') border-red-500 @enderror"
                           placeholder="Kosongkan jika tidak diubah">

                    <button type="button"
                            onclick="togglePassword('newPassword', this)"
                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                @error('password')
                    <p class="text-red-500 text-xs mt-1">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror

                <p class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>Minimal 6 karakter
                </p>
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Konfirmasi Password Baru
                </label>

                <div class="relative">
                    <input type="password"
                           name="password_confirmation"
                           id="passwordConfirmation"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 text-sm focus:outline-none focus:ring-0 focus:border-gray-500 @error('password') border-red-500 @enderror"
                           placeholder="Ulangi password baru">

                    <button type="button"
                            onclick="togglePassword('passwordConfirmation', this)"
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


            <!-- Role -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Role <span class="text-red-500">*</span>
                </label>

                <input type="hidden" name="role" id="roleValue"
                       value="{{ old('role', $pengguna->role) }}">

                <div class="relative">
                    <button type="button"
                        onclick="document.getElementById('roleDropdown').classList.toggle('hidden')"
                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2 text-sm flex justify-between items-center hover:bg-gray-50 focus:outline-none focus:ring-0 focus:border-gray-500 @error('role') border-red-500 @enderror">

                        <span id="roleText">
                            {{ old('role', $pengguna->role) }}
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

        <!-- Warning -->
        <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-4">
            <p class="text-sm text-yellow-800">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Catatan:</strong> Untuk mengganti password, Anda harus mengisi password lama, password baru, dan konfirmasi password baru. Kosongkan semua field password jika tidak ingin mengubah password.
            </p>
        </div>

        <!-- Action -->
        <div class="mt-6 pt-6 border-t border-gray-200 flex sm:flex-row gap-3">
            <button type="submit"
                    class="bg-gray-700 hover:bg-gray-800 text-white px-6 py-2.5 rounded-lg transition text-sm font-medium">
                <i class="fas fa-save mr-2"></i>Update
            </button>

            <a href="{{ route('pengguna.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2.5 rounded-lg transition text-sm font-medium text-center">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

    </form>
</div>

<script>
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

    // Validasi password di sisi client (opsional)
    document.querySelector('form').addEventListener('submit', function(e) {
        const currentPassword = document.getElementById('currentPassword').value;
        const newPassword = document.getElementById('newPassword').value;
        const passwordConfirmation = document.getElementById('passwordConfirmation').value;

        // Jika user mengisi password baru tapi tidak mengisi password lama
        if (newPassword && !currentPassword) {
            e.preventDefault();
            alert('Password lama harus diisi jika ingin mengganti password.');
            document.getElementById('currentPassword').focus();
            return false;
        }

        // Jika password baru dan konfirmasi tidak sama
        if (newPassword && newPassword !== passwordConfirmation) {
            e.preventDefault();
            alert('Password baru dan konfirmasi password tidak sama.');
            document.getElementById('passwordConfirmation').focus();
            return false;
        }
    });
</script>

@endsection