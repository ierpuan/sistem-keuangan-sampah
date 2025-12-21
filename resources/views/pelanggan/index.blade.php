@extends('layouts.app')

@section('title', 'Data Pelanggan')

@section('content')
<div class="mb-3">
    <h1 class="text-2xl font-bold text-gray-800">Data Pelanggan</h1>
    <p class="text-sm text-gray-600">Kelola informasi pelanggan</p>
</div>
<div class="flex justify-end">
        <a href="{{ route('pelanggan.create') }}"
           class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg transition text-sm whitespace-nowrap mb-3">
            <i class="fas fa-plus mr-1"></i>Tambah Pelanggan
        </a>
</div>

<!-- Filter -->
<div class="bg-white rounded-lg shadow mb-4">
    <!-- Toggle Filter Button (Mobile) -->
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
        <form method="GET" action="{{ route('pelanggan.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <!-- Search -->
                <div class="sm:col-span-2 lg:col-span-1">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text"
                           name="search"
                           autocomplete="off"
                           {{-- value="{{ request('search') }" --}}
                           class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-0 focus:border-gray-300"
                           placeholder="Nama atau alamat...">
                </div>

                <!-- RT -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">RT</label>

                    <div class="relative">
                        <button type="button"
                            onclick="document.getElementById('rtDropdown').classList.toggle('hidden')"
                            class="w-full bg-white border border-gray-300 rounded px-3 py-1.5 text-sm
                                   flex justify-between items-center hover:bg-gray-50">
                            <span>{{ request('rt') ? 'RT ' . request('rt') : 'Semua' }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        <div id="rtDropdown"
                             class="hidden absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-md max-h-48 overflow-auto">
                            <a href="{{ route('pelanggan.index', request()->except('rt')) }}"
                               class="block px-3 py-2 text-sm hover:bg-gray-100">
                                Semua
                            </a>

                            @foreach($rt_list as $r)
                                <a href="{{ route('pelanggan.index', array_merge(request()->all(), ['rt' => $r])) }}"
                                   class="block px-3 py-2 text-sm hover:bg-gray-100">
                                    RT {{ $r }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>


                <!-- RW -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">RW</label>

                    <div class="relative">
                        <button type="button"
                            onclick="document.getElementById('rwDropdown').classList.toggle('hidden')"
                            class="w-full bg-white border border-gray-300 rounded px-3 py-1.5 text-sm
                                   flex justify-between items-center hover:bg-gray-50">
                            <span>{{ request('rw') ? 'RW ' . request('rw') : 'Semua' }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        <div id="rwDropdown"
                             class="hidden absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-md max-h-48 overflow-auto">
                            <a href="{{ route('pelanggan.index', request()->except('rw')) }}"
                               class="block px-3 py-2 text-sm hover:bg-gray-100">
                                Semua
                            </a>

                            @foreach($rw_list as $rw)
                                <a href="{{ route('pelanggan.index', array_merge(request()->all(), ['rw' => $rw])) }}"
                                   class="block px-3 py-2 text-sm hover:bg-gray-100">
                                    RW {{ $rw }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>


                <!-- Dusun -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Dusun</label>

                    <div class="relative">
                        <button type="button"
                            onclick="document.getElementById('dusunDropdown').classList.toggle('hidden')"
                            class="w-full bg-white border border-gray-300 rounded px-3 py-1.5 text-sm
                                   flex justify-between items-center hover:bg-gray-50">
                            <span>{{ request('dusun') ?? 'Semua' }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        <div id="dusunDropdown"
                             class="hidden absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-md max-h-48 overflow-auto">
                            <a href="{{ route('pelanggan.index', request()->except('dusun')) }}"
                               class="block px-3 py-2 text-sm hover:bg-gray-100">
                                Semua
                            </a>

                            @foreach($dusun_list as $dusun)
                                <a href="{{ route('pelanggan.index', array_merge(request()->all(), ['dusun' => $dusun])) }}"
                                   class="block px-3 py-2 text-sm hover:bg-gray-100">
                                    {{ $dusun }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>


                <!-- Tombol -->
                <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-1">
                    <button type="submit"
                            class="flex-1 bg-gray-700 hover:bg-gray-800 text-white px-3 py-1.5 rounded text-sm transition">
                        <i class="fas fa-filter mr-1"></i>Filter
                    </button>
                    <a href="{{ route('pelanggan.index') }}"
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
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase hidden sm:table-cell">Alamat</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase hidden md:table-cell">Dusun</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase">Status</th>
                    <th class="px-3 py-3 text-center text-xs font-semibold text-white uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pelanggan as $index => $p)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-3 py-3 text-sm text-gray-900 whitespace-nowrap">
                            {{ $pelanggan->firstItem() + $index }}
                        </td>
                        <td class="px-3 py-3">
                            <div class="font-medium text-sm text-gray-900">{{ $p->nama }}</div>
                            <div class="text-xs text-gray-500 sm:hidden mt-1">{{ $p->alamat_lengkap }}</div>
                            <div class="text-xs text-gray-500 md:hidden mt-1">
                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $p->dusun ?? '-' }}
                            </div>
                        </td>
                        <td class="px-3 py-3 text-sm text-gray-700 hidden sm:table-cell">
                            <div class="max-w-xs truncate">{{ $p->alamat_lengkap }}</div>
                        </td>
                        <td class="px-3 py-3 text-sm text-gray-700 whitespace-nowrap hidden md:table-cell">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>{{ $p->dusun ?? '-' }}
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $p->status_aktif === 'Aktif' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                {{ $p->status_aktif }}
                            </span>
                        </td>
                        <td class="px-3 py-3 text-center text-sm whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('pelanggan.show', $p->id_pelanggan) }}"
                                   class="text-gray-600 hover:text-gray-800 transition p-1"
                                   title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('pelanggan.edit', $p->id_pelanggan) }}"
                                   class="text-yellow-600 hover:text-yellow-800 transition p-1"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('pelanggan.destroy', $p->id_pelanggan) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800 transition p-1"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-3 py-8 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="fas fa-users text-5xl mb-3"></i>
                                <p class="text-base font-medium text-gray-500">Tidak ada data pelanggan</p>
                                <p class="text-sm text-gray-400 mt-1">Silakan tambah pelanggan baru</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
        {{ $pelanggan->links() }}
    </div>
</div>
@endsection