@extends('layouts.app')

@section('title', 'Data Pengeluaran')

@section('content')
<div class="mb-3">
    <h1 class="text-2xl font-bold text-gray-800">Data Pengeluaran</h1>
    <p class="text-sm text-gray-600">Kelola pengeluaran operasional</p>
</div>
<div class="flex justify-end">
    <a href="{{ route('pengeluaran.create') }}"
       class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded transition text-sm whitespace-nowrap mb-3">
        <i class="fas fa-plus mr-1"></i>Tambah Pengeluaran
    </a>
</div>

<!-- Summary Card -->
<div class="bg-white rounded-lg shadow p-4 mb-4 text-gray-700">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-700 text-sm font-semibold mb-1">Total Pengeluaran</p>
            <p class="text-2xl font-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white bg-opacity-20 p-3 rounded-full">
            <i class="fas fa-money-bill-wave text-2xl"></i>
        </div>
    </div>
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
    <form id="filterForm" method="GET" action="{{ route('pengeluaran.index') }}" class="hidden lg:block p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Kategori -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Pengeluaran</label>

                <div class="relative">
                    <button type="button"
                        onclick="document.getElementById('kategoriDropdown').classList.toggle('hidden')"
                        class="w-full bg-white border border-gray-300 rounded px-2 py-1.5 text-sm
                               flex justify-between items-center hover:bg-gray-50">
                        <span>{{ request('kategori') ?? 'Semua Jenis' }}</span>
                        <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                    </button>

                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">

                    <div id="kategoriDropdown"
                         class="hidden absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-md max-h-56 overflow-auto">
                        <a href="javascript:void(0)"
                           onclick="document.querySelector('input[name=kategori]').value = ''; document.getElementById('filterForm').submit();"
                           class="block px-3 py-2 text-sm hover:bg-gray-100">
                            Semua Jenis
                        </a>

                        @foreach($kategoriList as $kat)
                            <a href="javascript:void(0)"
                               onclick="document.querySelector('input[name=kategori]').value = '{{ $kat }}'; document.getElementById('filterForm').submit();"
                               class="block px-3 py-2 text-sm hover:bg-gray-100 {{ request('kategori') == $kat ? 'bg-gray-100 font-medium' : '' }}">
                                {{ $kat }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date"
                       name="dari_tanggal"
                       value="{{ request('dari_tanggal') }}"
                       class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm
                              focus:outline-none focus:ring-0 focus:border-gray-500">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date"
                       name="sampai_tanggal"
                       value="{{ request('sampai_tanggal') }}"
                       class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm
                              focus:outline-none focus:ring-0 focus:border-gray-500">
            </div>

            <!-- Tombol Filter -->
            <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-1">
                <button type="submit"
                    class="flex-1 bg-gray-700 hover:bg-gray-800 text-white px-3 py-1.5 rounded text-sm transition">
                    <i class="fas fa-filter mr-1"></i>Filter
                </button>
                <a href="{{ route('pengeluaran.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1.5 rounded text-sm transition">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-700 to-gray-800">
                <tr>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase">No</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase">Tanggal</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase hidden md:table-cell">Jenis</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase hidden lg:table-cell">Keterangan</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase">Jumlah</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase hidden xl:table-cell">Dicatat Oleh</th>
                    <th class="px-3 py-3 text-center text-xs font-semibold text-white uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pengeluaran as $index => $p)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-3 py-3 text-sm text-gray-900 whitespace-nowrap">
                            {{ $pengeluaran->firstItem() + $index }}
                        </td>
                        <td class="px-3 py-3">
                            <div class="font-medium text-sm text-gray-900">
                                {{ $p->tanggal_pengeluaran->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-500 md:hidden mt-1">
                                <span class="inline-block px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800">
                                    {{ $p->kategori }}
                                </span>
                            </div>
                            <div class="text-xs text-gray-500 lg:hidden mt-1">
                                {{ Str::limit($p->keterangan ?? '-', 30) }}
                            </div>
                            <div class="text-xs text-gray-500 xl:hidden mt-1">
                                <i class="fas fa-user text-gray-400 mr-1"></i>{{ $p->pengguna->nama }}
                            </div>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap hidden md:table-cell">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ $p->kategori }}
                            </span>
                        </td>
                        <td class="px-3 py-3 text-sm text-gray-700 hidden lg:table-cell">
                            <div class="max-w-xs truncate">{{ $p->keterangan ?? '-' }}</div>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            <div class="font-bold text-sm text-gray-900">
                                Rp {{ number_format($p->jumlah, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-3 py-3 text-sm text-gray-700 whitespace-nowrap hidden xl:table-cell">
                            <i class="fas fa-user text-gray-400 mr-1"></i>{{ $p->pengguna->nama }}
                        </td>
                        <td class="px-3 py-3 text-center text-sm whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('pengeluaran.show', $p->id_pengeluaran) }}"
                                   class="text-gray-600 hover:text-gray-800 transition p-1"
                                   title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('pengeluaran.edit', $p->id_pengeluaran) }}"
                                   class="text-yellow-600 hover:text-yellow-800 transition p-1"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('pengeluaran.destroy', $p->id_pengeluaran) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Yakin ingin menghapus pengeluaran ini?')">
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
                        <td colspan="7" class="px-3 py-8 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="fas fa-money-bill-wave text-5xl mb-3"></i>
                                <p class="text-base font-medium text-gray-500">Tidak ada data pengeluaran</p>
                                <p class="text-sm text-gray-400 mt-1">Silakan tambah pengeluaran baru</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
        {{ $pengeluaran->links() }}
    </div>
</div>
@endsection