<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Operasional')</title>

    @vite('resources/css/app.css')

    <!-- Font Awesome & Leaflet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased">

<div class="flex min-h-screen">

    {{-- SIDEBAR OVERLAY (Mobile) --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

    {{-- SIDEBAR --}}
    <aside id="sidebar" class="fixed top-0 left-0 h-screen w-64 bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900 shadow-2xl flex flex-col z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">

        {{-- HEADER SIDEBAR --}}
        <div class="px-6 py-6 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-gray-500 to-gray-600 rounded-lg flex items-center justify-center shadow-lg">
                        <i class="fas fa-recycle text-white text-xl"></i>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-white leading-tight">
                            Sistem Keuangan
                        </div>
                        <div class="text-xs text-gray-400 font-medium">
                            Desa Sambopinggir
                        </div>
                    </div>
                </div>
                {{-- Close Button (Mobile) --}}
                <button id="close-sidebar" class="lg:hidden text-gray-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        {{-- MENU --}}
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-700">

            <x-sidebar-link route="dashboard" icon="fas fa-house" label="Dashboard" />

            {{-- Divider --}}
            <div class="pt-4 pb-2">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3">
                    Menu Utama
                </div>
            </div>

            {{-- Semua role (Admin & Petugas) --}}
            <x-sidebar-link route="tagihan.index" icon="fas fa-file-invoice" label="Tagihan" />
            <x-sidebar-link route="lokasi.index" icon="fas fa-map-location-dot" label="Lokasi" />

            {{-- MENU ADMIN ONLY --}}
            @if(auth()->check() && auth()->user()->role === 'Admin')
                <div class="pt-4 pb-2">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3">
                        Administrator
                    </div>
                </div>
                <x-sidebar-link route="pelanggan.index" icon="fas fa-users" label="Pelanggan" />
                <x-sidebar-link route="pengeluaran.index" icon="fas fa-wallet" label="Pengeluaran" />
                <x-sidebar-link route="laporan.index" icon="fas fa-chart-line" label="Laporan Keuangan" />
                <x-sidebar-link route="pengguna.index" icon="fas fa-user-gear" label="Pengguna" />
            @endif
        </nav>

        {{-- LOGOUT SECTION --}}
        <div class="px-4 py-4 border-t border-gray-700">
            @if(auth()->check())
                <div class="bg-gray-800 rounded-lg p-3 mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-500 to-gray-600 flex items-center justify-center text-white text-sm font-bold shadow-lg">
                            {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-semibold text-white truncate">
                                {{ auth()->user()->nama }}
                            </div>
                            <div class="text-xs text-gray-400 capitalize">
                                {{ auth()->user()->role }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-lg text-red-300 hover:bg-red-600 hover:text-white transition-all duration-200 group">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- AREA KANAN --}}
    <div class="flex-1 min-w-0 flex flex-col lg:ml-64">


        {{-- TOPBAR --}}
        <header class="fixed top-0 left-0 lg:left-64 right-0 h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 lg:px-8 z-30 shadow-sm">
            <div class="flex items-center gap-3">
                {{-- Hamburger Menu (Mobile) --}}
                <button id="open-sidebar" class="lg:hidden text-gray-600 hover:text-gray-800 p-2">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <div class="text-base lg:text-lg font-bold text-gray-800">
                    Layanan Pengelolaan Sampah
                </div>
            </div>

            <div class="flex items-center gap-2 lg:gap-4">
                {{-- Waktu Real-time --}}
                <div class="text-xs lg:text-sm text-gray-600 hidden sm:block">
                    <i class="far fa-clock mr-1"></i>
                    <span id="current-time"></span>
                </div>

                {{-- User Avatar (Mobile) --}}
                @if(auth()->check())
                    <div class="lg:hidden w-8 h-8 rounded-full bg-gradient-to-br from-gray-500 to-gray-600 flex items-center justify-center text-white text-xs font-bold shadow-lg">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    </div>
                @endif
            </div>
        </header>

        {{-- KONTEN --}}
        <main class="flex-1 min-w-0 pt-20 px-4 lg:px-8 pb-8 overflow-x-auto">

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 p-3 lg:p-4 rounded-lg shadow-sm animate-fade-in">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-600"></i>
                        <span class="text-sm lg:text-base font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-800 p-3 lg:p-4 rounded-lg shadow-sm animate-fade-in">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-exclamation-circle text-red-600"></i>
                        <span class="text-sm lg:text-base font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 text-blue-800 p-3 lg:p-4 rounded-lg shadow-sm animate-fade-in">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-600"></i>
                        <span class="text-sm lg:text-base font-medium">{{ session('info') }}</span>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        {{-- FOOTER --}}
        <footer class="bg-white border-t border-gray-200 px-4 lg:px-8 py-3 lg:py-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-xs lg:text-sm text-gray-600">
                <div class="text-center sm:text-left">
                    &copy; {{ date('Y') }} Desa Sambopinggir. All rights reserved.
                </div>
                <div class="flex items-center gap-4">
                    <span>Version 1.0.0</span>
                </div>
            </div>
        </footer>
    </div>
</div>

{{-- Real-time Clock Script --}}
<script>
    // Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const openSidebar = document.getElementById('open-sidebar');
    const closeSidebar = document.getElementById('close-sidebar');

    openSidebar?.addEventListener('click', () => {
        sidebar.classList.remove('-translate-x-full');
        sidebarOverlay.classList.remove('hidden');
    });

    closeSidebar?.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.add('hidden');
    });

    sidebarOverlay?.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.add('hidden');
    });

    // Real-time Clock
    function updateTime() {
        const now = new Date();
        const options = {
            weekday: 'short',
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        const timeString = now.toLocaleDateString('id-ID', options);
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            timeElement.textContent = timeString;
        }
    }

    updateTime();
    setInterval(updateTime, 1000);

    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.animate-fade-in');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });
    });
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }

    /* Custom Scrollbar */
    .scrollbar-thin::-webkit-scrollbar {
        width: 6px;
    }
    .scrollbar-thin::-webkit-scrollbar-track {
        background: transparent;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb {
        background: #4B5563;
        border-radius: 3px;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
        background: #6B7280;
    }
</style>

@stack('scripts')
</body>
</html>