@extends('layouts.app')

@section('title', 'Data Tagihan')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Data Tagihan</h1>
        <p class="text-gray-600">Kelola tagihan pelanggan</p>
    </div>
    <div class="flex gap-2">
        <button onclick="document.getElementById('modalGenerate').classList.remove('hidden')"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-plus-circle mr-2"></i>Generate Tagihan Massal
        </button>
        <button>
            <a href="{{ route('tagihan.pdf', ['periode' => request('periode')]) }}"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded"
                target="_blank">
                <i class="fas fa-file-pdf"></i> Download PDF
             </a>
        </button>
        {{-- <div class="flex gap-2 mb-4">
            <a href="{{ route('tagihan.cetak.semua') }}"
            target="_blank"
            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-print mr-2"></i>Cetak Semua
            </a>
        </div> --}}


        {{-- <a href="{{ route('tagihan.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Tambah Tagihan
        </a> --}}
    </div>
</div>

<!-- Filter -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="{{ route('tagihan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Pelanggan</label>
            <input
                type="text"
                name="pelanggan"
                value="{{ request('pelanggan') }}"
                placeholder="Masukkan nama pelanggan"
                class="w-full border rounded-lg px-4 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
            <select name="periode" class="w-full border rounded-lg px-4 py-2">
                <option value="">Semua Periode</option>
                @foreach($periode_list as $p)
                    <option value="{{ $p }}" {{ request('periode') === $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full border rounded-lg px-4 py-2">
                <option value="">Semua Status</option>
                <option value="Belum Bayar" {{ request('status') === 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                <option value="Tunggakan" {{ request('status') === 'Tunggakan' ? 'selected' : '' }}>Tunggakan</option>
                <option value="Lunas" {{ request('status') === 'Lunas' ? 'selected' : '' }}>Lunas</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-search mr-1"></i>Filter
            </button>
            <a href="{{ route('tagihan.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                Reset
            </a>
        </div>
    </form>
</div>
<form action="{{ route('tagihan.cetak.periode') }}" method="GET" target="_blank">
    <select name="periode" required>
        @foreach($periode_list as $p)
            <option value="{{ $p }}">{{ $p }}</option>
        @endforeach
    </select>
    <button type="submit">Cetak</button>
</form>

<!-- Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tagihan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Terbayar</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sisa</th>
                {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th> --}}
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Deposit</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($tagihan as $index => $t)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $tagihan->firstItem() + $index }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $t->pelanggan->nama }}</div>
                        <div class="text-sm text-gray-500">{{ $t->pelanggan->dusun }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">{{ $t->periode }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">Rp {{ number_format($t->jml_tagihan_pokok, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">Rp {{ number_format($t->total_sudah_bayar, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-semibold">Rp {{ number_format($t->sisa_tagihan, 0, ',', '.') }}</td>
                    {{-- <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $t->jatuh_tempo->format('d/m/Y') }}</td> --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $t->status === 'Lunas' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $t->status === 'Tunggakan' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $t->status === 'Belum Bayar' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                            {{ $t->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">
                        Rp {{ number_format($t->pelanggan->deposit->saldo_deposit, 0, ',', '.') }}

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <a href="{{ route('tagihan.show', $t->id_tagihan) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($t->status !== 'Lunas')
                            <a href="{{ route('pembayaran.create', $t->id_tagihan) }}" class="text-green-600 hover:text-green-900 mr-3">
                                <i class="fas fa-money-bill-wave"></i>
                            </a>
                        @endif
                        @if($t->transaksiPembayaran->count() === 0)
                            <a href="{{ route('tagihan.edit', $t->id_tagihan) }}"
                            class="text-yellow-600 hover:text-yellow-800"
                            title="Edit Tagihan">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endif
                        {{-- @if($t->transaksiPembayaran->count() === 0)
                            <form action="{{ route('tagihan.destroy', $t->id_tagihan) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif --}}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-6 py-4 text-center text-gray-500">Tidak ada data tagihan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $tagihan->links() }}
</div>

<!-- Modal Generate Tagihan -->
<div id="modalGenerate" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Generate Tagihan Massal</h3>
            <form method="POST" action="{{ route('tagihan.generate-bulk') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode (YYYY-MM)</label>
                    <input type="month" name="periode" class="w-full border rounded-lg px-4 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Tagihan</label>
                    <input type="number" name="jml_tagihan_pokok" class="w-full border rounded-lg px-4 py-2"
                           value="10000" required>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                        Generate
                    </button>
                    <button type="button" onclick="document.getElementById('modalGenerate').classList.add('hidden')"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection