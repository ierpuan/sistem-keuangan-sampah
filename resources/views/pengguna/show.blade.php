@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')

<!-- Header -->
<div class="mb-3">
    <h1 class="text-2xl font-bold text-gray-800">Detail Pengguna</h1>
    <p class="text-sm text-gray-600">Informasi lengkap akun pengguna</p>
</div>

<!-- Tombol -->
<div class="flex flex-wrap gap-2 mb-4">
    <a href="{{ route('pengguna.edit', $pengguna->id_pengguna) }}"
       class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded text-sm">
        <i class="fas fa-edit mr-1"></i>Edit
    </a>

    <button onclick="document.getElementById('modalResetPassword').classList.remove('hidden')"
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">
        <i class="fas fa-key mr-1"></i>Reset Password
    </button>

    <a href="{{ route('pengguna.index') }}"
       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded text-sm">
        <i class="fas fa-arrow-left mr-1"></i>Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    <!-- Profil -->
    <div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center gap-4 mb-4">
                <div class="h-14 w-14 rounded-full bg-gray-700 flex items-center justify-center
                            text-white text-xl font-bold">
                    {{ strtoupper(substr($pengguna->nama, 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-gray-800">{{ $pengguna->nama }}</p>
                    <p class="text-sm text-gray-600">{{ $pengguna->username }}</p>
                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-xs
                        {{ $pengguna->role === 'Admin'
                            ? 'bg-gray-800 text-white'
                            : 'bg-gray-200 text-gray-800' }}">
                        {{ $pengguna->role }}
                    </span>
                </div>
            </div>

            <div class="text-sm text-gray-600 space-y-2 border-t pt-3">
                <div class="flex justify-between">
                    <span>Terdaftar</span>
                    <span class="font-medium">{{ $pengguna->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Bergabung</span>
                    <span class="font-medium">{{ $pengguna->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="bg-white rounded-lg shadow p-4 mt-4">
            <h3 class="font-semibold text-gray-800 mb-3 text-sm">
                Statistik Aktivitas
            </h3>

            <div class="space-y-3">
                <div class="border rounded p-3">
                    <p class="text-xs text-gray-600">Total Transaksi Pembayaran</p>
                    <p class="text-lg font-bold text-gray-800">
                        {{ $stats['total_transaksi'] }}
                    </p>
                    <p class="text-xs text-gray-500">
                        Rp {{ number_format($stats['total_pembayaran'], 0, ',', '.') }}
                    </p>
                </div>

                <div class="border rounded p-3">
                    <p class="text-xs text-gray-600">Pengeluaran Dicatat</p>
                    <p class="text-lg font-bold text-gray-800">
                        {{ $stats['total_pengeluaran_dicatat'] }}
                    </p>
                    <p class="text-xs text-gray-500">
                        Rp {{ number_format($stats['total_nilai_pengeluaran'], 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Aktivitas -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-4 py-3 border-b">
                <h3 class="font-semibold text-gray-800 text-sm">
                    Transaksi Pembayaran Terbaru
                </h3>
            </div>

            @if($transaksi_terbaru->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs text-gray-500 uppercase">Tanggal</th>
                                <th class="px-3 py-3 text-left text-xs text-gray-500 uppercase">Pelanggan</th>
                                <th class="px-3 py-3 text-left text-xs text-gray-500 uppercase">Periode</th>
                                <th class="px-3 py-3 text-right text-xs text-gray-500 uppercase">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($transaksi_terbaru as $t)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 text-sm">
                                        {{ $t->tgl_bayar->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-3 py-2 text-sm">
                                        {{ $t->tagihan->pelanggan->nama }}
                                    </td>
                                    <td class="px-3 py-2 text-sm">
                                        {{ $t->tagihan->periode }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-right font-bold">
                                        Rp {{ number_format($t->jml_bayar_input, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-8 text-center text-sm text-gray-500">
                    <i class="fas fa-receipt text-4xl mb-2 text-gray-300"></i>
                    <p>Belum ada transaksi</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Reset Password -->
<div id="modalResetPassword"
     class="hidden fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow w-full max-w-sm p-4">
        <h3 class="font-semibold text-gray-800 mb-3">
            <i class="fas fa-key mr-2 text-gray-600"></i>Reset Password
        </h3>

        <form method="POST" id="formResetPassword"
              action="{{ route('pengguna.reset-password', $pengguna->id_pengguna) }}">
            @csrf

            <!-- Password Lama -->
            <div class="mb-3">
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    Password Lama <span class="text-red-500">*</span>
                </label>
                <input type="password"
                       name="current_password"
                       id="modalCurrentPassword"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm
                              focus:outline-none focus:ring-0 focus:border-gray-500"
                       placeholder="Masukkan password lama"
                       required>
                <p class="text-xs text-red-500 mt-1 hidden" id="errorCurrentPassword"></p>
            </div>

            <!-- Password Baru -->
            <div class="mb-3">
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    Password Baru <span class="text-red-500">*</span>
                </label>

                <div class="relative">
                    <input type="password"
                           name="new_password"
                           id="modalNewPassword"
                           class="w-full border border-gray-300 rounded px-3 py-2 pr-10 text-sm
                                  focus:outline-none focus:ring-0 focus:border-gray-500"
                           placeholder="Minimal 6 karakter"
                           required>

                    <button type="button"
                            onclick="togglePasswordModal('modalNewPassword', this)"
                            class="absolute inset-y-0 right-0 px-3 flex items-center
                                   text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="fas fa-eye text-sm"></i>
                    </button>
                </div>

                <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                <p class="text-xs text-red-500 mt-1 hidden" id="errorNewPassword"></p>
            </div>


            <!-- Konfirmasi Password -->
            <div class="mb-3">
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    Konfirmasi Password Baru <span class="text-red-500">*</span>
                </label>

                <div class="relative">
                    <input type="password"
                           name="new_password_confirmation"
                           id="modalPasswordConfirmation"
                           class="w-full border border-gray-300 rounded px-3 py-2 pr-10 text-sm
                                  focus:outline-none focus:ring-0 focus:border-gray-500"
                           placeholder="Ulangi password baru"
                           required>

                    <button type="button"
                            onclick="togglePasswordModal('modalPasswordConfirmation', this)"
                            class="absolute inset-y-0 right-0 px-3 flex items-center
                                   text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="fas fa-eye text-sm"></i>
                    </button>
                </div>

                <p class="text-xs text-red-500 mt-1 hidden" id="errorPasswordConfirmation"></p>
            </div>


            <!-- Warning Box -->
            <div class="mb-4 bg-yellow-50 border border-yellow-200 rounded p-2">
                <p class="text-xs text-yellow-800">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Pastikan password lama Anda benar dan password baru minimal 6 karakter.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 bg-gray-700 hover:bg-gray-800 text-white px-3 py-2 rounded text-sm
                               transition duration-150">
                    <i class="fas fa-check mr-1"></i>Reset Password
                </button>
                <button type="button"
                        onclick="closeResetPasswordModal()"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-2 rounded text-sm
                               transition duration-150">
                    <i class="fas fa-times mr-1"></i>Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fungsi untuk membuka modal reset password
    function openResetPasswordModal() {
        document.getElementById('modalResetPassword').classList.remove('hidden');
        // Reset form dan error messages
        document.getElementById('formResetPassword').reset();
        clearResetPasswordErrors();
    }

    // Fungsi untuk menutup modal reset password
    function closeResetPasswordModal() {
        document.getElementById('modalResetPassword').classList.add('hidden');
        // Reset form dan error messages
        document.getElementById('formResetPassword').reset();
        clearResetPasswordErrors();
    }

    // Fungsi untuk clear error messages
    function clearResetPasswordErrors() {
        document.getElementById('errorCurrentPassword').classList.add('hidden');
        document.getElementById('errorNewPassword').classList.add('hidden');
        document.getElementById('errorPasswordConfirmation').classList.add('hidden');

        // Reset border colors
        document.getElementById('modalCurrentPassword').classList.remove('border-red-500');
        document.getElementById('modalNewPassword').classList.remove('border-red-500');
        document.getElementById('modalPasswordConfirmation').classList.remove('border-red-500');
    }

    // Validasi form sebelum submit
    document.getElementById('formResetPassword').addEventListener('submit', function(e) {
        clearResetPasswordErrors();

        let isValid = true;
        const currentPassword = document.getElementById('modalCurrentPassword');
        const newPassword = document.getElementById('modalNewPassword');
        const passwordConfirmation = document.getElementById('modalPasswordConfirmation');

        // Validasi password lama tidak boleh kosong
        if (!currentPassword.value.trim()) {
            showError('errorCurrentPassword', 'Password lama harus diisi', currentPassword);
            isValid = false;
        }

        // Validasi password baru minimal 6 karakter
        if (newPassword.value.length < 6) {
            showError('errorNewPassword', 'Password baru minimal 6 karakter', newPassword);
            isValid = false;
        }

        // Validasi konfirmasi password harus sama dengan password baru
        if (newPassword.value !== passwordConfirmation.value) {
            showError('errorPasswordConfirmation', 'Konfirmasi password tidak sama', passwordConfirmation);
            isValid = false;
        }

        // Validasi password baru tidak boleh sama dengan password lama
        if (currentPassword.value === newPassword.value) {
            showError('errorNewPassword', 'Password baru harus berbeda dengan password lama', newPassword);
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });
    function togglePasswordModal(inputId, btn) {
        const input = document.getElementById(inputId);
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
    // Fungsi helper untuk menampilkan error
    function showError(errorId, message, inputElement) {
        const errorElement = document.getElementById(errorId);
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
        inputElement.classList.add('border-red-500');
    }

    // Close modal jika klik di luar modal
    document.getElementById('modalResetPassword').addEventListener('click', function(e) {
        if (e.target === this) {
            closeResetPasswordModal();
        }
    });

    // Close modal dengan tombol ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('modalResetPassword');
            if (!modal.classList.contains('hidden')) {
                closeResetPasswordModal();
            }
        }
    });

</script>

<!-- Tampilkan error dari server jika ada -->
@if($errors->has('current_password') || $errors->has('new_password'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Buka modal jika ada error
        openResetPasswordModal();

        @if($errors->has('current_password'))
            showError('errorCurrentPassword', '{{ $errors->first('current_password') }}',
                     document.getElementById('modalCurrentPassword'));
        @endif

        @if($errors->has('new_password'))
            showError('errorNewPassword', '{{ $errors->first('new_password') }}',
                     document.getElementById('modalNewPassword'));
        @endif
    });
</script>
@endif

@endsection
