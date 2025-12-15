@extends('layouts.app')

@section('title', 'Data Pengeluaran')

@section('content')
<div class="mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Data Pengeluaran</h1>
        <p class="text-gray-600">Kelola pengeluaran operasional</p>
    </div>
    <div class="flex justify-end">
    <a href="{{ route('pengeluaran.create') }}"class="mt-4 bg-gray-300 hover:bg-gray-800 text-gray-800 hover:text-gray-300 px-3 py-2 rounded-lg">
        <i class="fas fa-plus mr-2"></i>Tambah Pengeluaran
    </a>
    </div>
</div>

<!-- Filter -->
<div class="w-auto bg-white rounded-lg shadow p-4 mb-4">
    <form method="GET" action="{{ route('pengeluaran.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <select name="kategori" class="w-full border rounded-lg px-3 py-1.5 text-sm">
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $kat)
                    <option value="{{ $kat }}" {{ request('kategori') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}"
            class="w-full border rounded-lg px-3 py-1.5 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}"
            class="w-full border rounded-lg px-3 py-1.5 text-sm">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-gray-300 hover:bg-gray-800 text-gray-800 hover:text-gray-300 px-4 py-1.5 rounded-lg text-sm flex items-center gap-1">
                <i class="fas fa-search mr-1"></i>Filter
            </button>
            <a href="{{ route('pengeluaran.index') }}" class="bg-gray-200 hover:bg-gray-400 text-gray-800 px-4 py-1.5 rounded-lg text-sm">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Summary Card -->
<div class="bg-gradient-to-g from-gray-500 to-gray-600 rounded-lg shadow p-4 mb-2 text-gray-800">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-8s00 text-sm mb-1">Total Pengeluaran</p>
            <p class="text-2xl font-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
        </div>
        <div class="bg-gray-600 bg-opacity-20 p-3 rounded-full">
            <i class="fas fa-money-bill-wave text-2xl"></i>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dicatat Oleh</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($pengeluaran as $index => $p)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $pengeluaran->firstItem() + $index }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $p->tanggal_pengeluaran->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full bg-gray-200 text-blue-600">
                            {{ $p->kategori }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $p->keterangan ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">
                        Rp {{ number_format($p->jumlah, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $p->pengguna->nama }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <a href="{{ route('pengeluaran.show', $p->id_pengeluaran) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                            Detail
                        <a href="{{ route('pengeluaran.edit', $p->id_pengeluaran) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">
                            Edit
                        </a>
                        <form action="{{ route('pengeluaran.destroy', $p->id_pengeluaran) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data pengeluaran</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $pengeluaran->links() }}
</div>
@endsection