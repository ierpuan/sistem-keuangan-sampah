@extends('layouts.auth')

@section('content')

<div class="w-full min-h-screen flex items-center justify-center bg-gray-100 p-4"> <div class="w-full max-w-md">
    <!-- Card Container -->
    <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">

        <!-- Icon -->
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-gray-500 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a3 3 0 00-3 3v1H6a2 2 0 00-2 2v7a2 2 0 002 2h8a2 2 0 002-2V8a2 2 0 00-2-2h-1V5a3 3 0 00-3-3zM9 5a1 1 0 112 0v1H9V5z" />
                </svg>
            </div>
        </div>

        <!-- Title & Subtitle -->
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                Selamat Datang
            </h2>
            <p class="text-gray-500 text-sm">
                Sistem Pengelolaan Sampah
            </p>
        </div>

        <!-- Error Alert -->
        @if($errors->any())
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded flex items-start gap-3">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm">{{ $errors->first() }}</span>
        </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Username Field -->
            <div class="space-y-1">
                <label for="username" class="block text-sm font-medium text-gray-700">
                    Username
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Masukkan username"
                        value="{{ old('username') }}"
                        required
                        autofocus
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg pl-10 pr-3 py-3 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500"
                    >
                </div>
            </div>

            <!-- Password Field -->
            <div class="space-y-1">
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Password
                </label>
                <div class="relative">
                    <!-- Icon gembok (tetap) -->
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>

                    <!-- Input password -->
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg pl-10 pr-10 py-3 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500"
                    >

                    <!-- Icon mata -->
                    <button
                        type="button"
                        onclick="togglePassword()"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                    >
                        <svg id="eyeIcon" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 11a4 4 0 110-8 4 4 0 010 8z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <script>
            function togglePassword() {
                const passwordInput = document.getElementById('password');
                const eyeIcon = document.getElementById('eyeIcon');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.innerHTML = `
                        <path d="M3.98 8.223A10.477 10.477 0 001 10c.73 2.89 4 7 9 7a9.77 9.77 0 004.47-1.02l-1.45-1.45A7.96 7.96 0 0110 14a4 4 0 01-3.46-6.02L3.98 8.223z"/>
                    `;
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.innerHTML = `
                        <path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 11a4 4 0 110-8 4 4 0 010 8z"/>
                    `;
                }
            }
            </script>


            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 rounded-lg shadow hover:shadow-md mt-4"
            >
                Masuk ke Sistem
            </button>
        </form>

        <!-- Footer Info -->
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-400">
                © 2025 Sistem Pengelolaan Sampah
            </p>
        </div>

    </div>
</div>
</div> @endsection