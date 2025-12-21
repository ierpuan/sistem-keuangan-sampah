@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')

<!-- Header -->
<div class="mb-3">
    <h1 class="text-2xl font-bold text-gray-800">Edit Pengguna</h1>
    <p class="text-sm text-gray-600">Perbarui data akun pengguna</p>
</div>

<!-- Form Card -->
<div class="bg-white rounded-lg shadow p-4">

    <form method="POST" action="{{ route('pengguna.update', $pengguna->id_pengguna) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- Nama -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="nama"
                       value="{{ old('nama', $pengguna->nama) }}"
                       class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm
                              focus:outline-none focus:ring-0 focus:border-gray-300
                              @error('nama') border-red-500 @enderror"
                       required>
                @error('nama')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Username -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    Username <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="username"
                       value="{{ old('username', $pengguna->username) }}"
                       class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm
                              focus:outline-none focus:ring-0 focus:border-gray-300
                              @error('username') border-red-500 @enderror"
                       required>
                @error('username')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    Password Baru
                </label>
                <input type="password"
                       name="password"
                       class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm
                              focus:outline-none focus:ring-0 focus:border-gray-300
                              @error('password') border-red-500 @enderror"
                       placeholder="Kosongkan jika tidak diubah">
                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">
                    Minimal 6 karakter
                </p>
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    Konfirmasi Password
                </label>
                <input type="password"
                       name="password_confirmation"
                       class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm
                              focus:outline-none focus:ring-0 focus:border-gray-300"
                       placeholder="Ulangi password baru">
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
                        class="w-full bg-white border rounded-lg px-4 py-2 text-sm
                               flex justify-between items-center hover:bg-gray-50
                               @error('role') border-red-500 @enderror">

                        <span id="roleText">
                            {{ old('role', $pengguna->role) }}
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

        <!-- Warning -->
        <div class="mt-4 bg-gray-50 border border-gray-200 rounded p-3">
            <p class="text-xs text-gray-600">
                <i class="fas fa-info-circle mr-1"></i>
                Password hanya akan diubah jika field password diisi.
            </p>
        </div>

        <!-- Action -->
        <div class="mt-4 flex gap-2">
            <button type="submit"
                    class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded text-sm">
                <i class="fas fa-save mr-1"></i>Update
            </button>

            <a href="{{ route('pengguna.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded text-sm">
                <i class="fas fa-times mr-1"></i>Batal
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
