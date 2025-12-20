@extends('layouts.app')

@section('title', 'Data Pengguna')

@section('content')

<!-- Header -->
<div class="mb-3">
        <h1 class="text-2xl font-bold text-gray-800">Data Pengguna</h1>
        <p class="text-sm text-gray-600">Kelola akun pengguna sistem</p>
</div>
    <div class="flex justify-end">
    <a href="{{ route('pengguna.create') }}"
       class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg text-sm transition whitespace-nowrap mb-3">
        <i class="fas fa-user-plus mr-1"></i>Tambah Pengguna
    </a>
    </div>

<!-- Filter -->
<div class="bg-white rounded-lg shadow mb-4">

    <!-- Toggle Filter (Mobile) -->
    <button type="button"
            onclick="document.getElementById('filterForm').classList.toggle('hidden')"
            class="lg:hidden w-full px-4 py-3 flex justify-between items-center text-left border-b border-gray-200">
        <span class="font-medium text-gray-700 text-sm">
            <i class="fas fa-filter mr-2"></i>Filter & Pencarian
        </span>
        <i class="fas fa-chevron-down text-gray-400"></i>
    </button>

    <!-- Filter Form -->
    <div id="filterForm" class="hidden lg:block p-4">
        <form method="GET" action="{{ route('pengguna.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                <!-- Search -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           autocomplete="off"
                           class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none"
                           placeholder="Nama atau Username">
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Role</label>

                    <div class="relative">
                        <button type="button"
                                onclick="document.getElementById('roleDropdown').classList.toggle('hidden')"
                                class="w-full bg-white border border-gray-300 rounded px-3 py-1.5 text-sm
                                       flex justify-between items-center hover:bg-gray-50">
                            <span>{{ request('role') ?? 'Semua Role' }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        <div id="roleDropdown"
                             class="hidden absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-md">
                            <a href="{{ route('pengguna.index', request()->except('role')) }}"
                               class="block px-3 py-2 text-sm hover:bg-gray-100">
                                Semua Role
                            </a>
                            <a href="{{ route('pengguna.index', array_merge(request()->all(), ['role' => 'Admin'])) }}"
                               class="block px-3 py-2 text-sm hover:bg-gray-100">
                                Admin
                            </a>
                            <a href="{{ route('pengguna.index', array_merge(request()->all(), ['role' => 'Petugas'])) }}"
                               class="block px-3 py-2 text-sm hover:bg-gray-100">
                                Petugas
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tombol -->
                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="flex-1 bg-gray-700 hover:bg-gray-800 text-white px-3 py-1.5 rounded text-sm transition">
                        <i class="fas fa-filter mr-1"></i>Filter
                    </button>
                    <a href="{{ route('pengguna.index') }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1.5 rounded text-sm transition">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-700 to-gray-800">
                <tr>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase">No</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase">Nama</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase hidden sm:table-cell">Username</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase">Role</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase hidden md:table-cell">Terdaftar</th>
                    <th class="px-3 py-3 text-center text-xs font-semibold text-white uppercase">Aksi</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pengguna as $index => $p)
                    <tr class="hover:bg-gray-50 transition
                        {{ $p->id_pengguna === auth()->id() ? 'bg-gray-50' : '' }}">
                        <td class="px-3 py-3 text-sm">
                            {{ $pengguna->firstItem() + $index }}
                        </td>
                        <td class="px-3 py-3">
                            <div class="font-medium text-sm text-gray-900">
                                {{ $p->nama }}
                                @if($p->id_pengguna === auth()->id())
                                    <span class="text-xs text-blue-600">(Anda)</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500 sm:hidden">
                                {{ $p->username }}
                            </div>
                        </td>
                        <td class="px-3 py-3 text-sm text-gray-600 hidden sm:table-cell">
                            {{ $p->username }}
                        </td>
                        <td class="px-3 py-3">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $p->role === 'Admin'
                                    ? 'bg-gray-800 text-white'
                                    : 'bg-gray-200 text-gray-800' }}">
                                {{ $p->role }}
                            </span>
                        </td>
                        <td class="px-3 py-3 text-sm text-gray-600 hidden md:table-cell">
                            {{ $p->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-3 py-3 text-center text-sm">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('pengguna.show', $p->id_pengguna) }}"
                                   class="text-gray-600 hover:text-gray-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('pengguna.edit', $p->id_pengguna) }}"
                                   class="text-yellow-600 hover:text-yellow-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($p->id_pengguna !== auth()->id())
                                    <form action="{{ route('pengguna.destroy', $p->id_pengguna) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-3 py-8 text-center">
                            <div class="flex flex-col items-center text-gray-400">
                                <i class="fas fa-user-slash text-5xl mb-3"></i>
                                <p class="text-base font-medium text-gray-500">
                                    Tidak ada data pengguna
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
        {{ $pengguna->links() }}
    </div>
</div>

@endsection
