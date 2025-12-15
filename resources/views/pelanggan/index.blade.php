@extends('layouts.app')

@section('title', 'Data Pelanggan')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Data Pelanggan</h1>
        <p class="text-gray-600">Kelola informasi pelanggan</p>
    </div>
    <a href="{{ route('pelanggan.create') }}"
       class="bg-gray-600 hover:bg-gray-800 text-white px-5 py-2 rounded-lg transition">
        <i class="fas fa-plus mr-2"></i>Tambah Pelanggan
    </a>
</div>

<!-- Filter -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="{{ route('pelanggan.index') }}">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1 text-gray-400"></i>Cari
                </label>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                       placeholder="Nama atau alamat...">
            </div>

            <!-- RT -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-map-marker-alt mr-1 text-gray-400"></i>RT
                </label>
                <select name="rt"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                    <option value="">Semua RT</option>
                    @foreach($rt_list as $r)
                        <option value="{{ $r }}" {{ request('rt') === $r ? 'selected' : '' }}>RT {{ $r }}</option>
                    @endforeach
                </select>
            </div>

            <!-- RW -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-map-marker-alt mr-1 text-gray-400"></i>RW
                </label>
                <select name="rw"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                    <option value="">Semua RW</option>
                    @foreach($rw_list as $rw)
                        <option value="{{ $rw }}" {{ request('rw') === $rw ? 'selected' : '' }}>RW {{ $rw }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Dusun -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-home mr-1 text-gray-400"></i>Dusun
                </label>
                <select name="dusun"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                    <option value="">Semua Dusun</option>
                    @foreach($dusun_list as $dusun)
                        <option value="{{ $dusun }}" {{ request('dusun') === $dusun ? 'selected' : '' }}>
                            {{ $dusun }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tombol -->
            <div class="flex items-end gap-2">
                <button type="submit"
                        class="flex-1 bg-gray-600 hover:bg-gray-800 text-white px-4 py-2 rounded-lg text-sm transition">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('pelanggan.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-sm transition">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-700 to-gray-800">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Alamat</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Dusun</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pelanggan as $index => $p)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $pelanggan->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $p->nama }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <div class="max-w-xs truncate">{{ $p->alamat_lengkap }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>{{ $p->dusun ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $p->status_aktif === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $p->status_aktif }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <div class="flex items-center justify-center gap-4">
                                <a href="{{ route('pelanggan.show', $p->id_pelanggan) }}"
                                   class="text-gray-600 hover:text-gray-800 transition"
                                   title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('pelanggan.edit', $p->id_pelanggan) }}"
                                   class="text-yellow-600 hover:text-yellow-800 transition"
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
                                            class="text-red-600 hover:text-red-800 transition"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="fas fa-users text-6xl mb-4"></i>
                                <p class="text-lg font-medium text-gray-500">Tidak ada data pelanggan</p>
                                <p class="text-sm text-gray-400 mt-1">Silakan tambah pelanggan baru</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        {{ $pelanggan->links() }}
    </div>
</div>
@endsection