@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="space-y-6">

    {{-- Filter & Export --}}
    <form method="GET" class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
        <div class="w-full sm:w-auto">
            <label class="block text-sm font-medium mb-1">Pilih Bulan / Tahun</label>
            <input type="month" name="bulan" value="{{ $bulan }}"
                   class="w-full sm:w-auto border rounded px-3 py-2">
        </div>

        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
            <button class="flex-1 sm:flex-none px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800 transition">
                Tampilkan
            </button>

            <a href="{{ route('laporan.export.pdf', ['bulan' => $bulan]) }}"
               class="flex-1 sm:flex-none px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition text-center">
                Export PDF
            </a>

            <a href="{{ route('laporan.export.excel', ['bulan' => $bulan]) }}"
               class="flex-1 sm:flex-none px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition text-center">
                Export Excel
            </a>
        </div>
    </form>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 bg-white shadow rounded">
            <p class="text-gray-500 text-sm">Total Pemasukan</p>
            <p class="text-xl font-bold text-green-600 mt-1">
                Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
            </p>
        </div>

        <div class="p-4 bg-white shadow rounded">
            <p class="text-gray-500 text-sm">Total Pengeluaran</p>
            <p class="text-xl font-bold text-red-600 mt-1">
                Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
            </p>
        </div>

        <div class="p-4 bg-white shadow rounded">
            <p class="text-gray-500 text-sm">Saldo</p>
            <p class="text-xl font-bold {{ $saldo >= 0 ? 'text-blue-600' : 'text-red-600' }} mt-1">
                Rp {{ number_format($saldo, 0, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- Tabel Pemasukan --}}
    <div class="bg-white shadow rounded p-4">
        <h2 class="font-semibold mb-3 text-lg">Pemasukan</h2>
        <div class="overflow-x-auto max-h-96 overflow-y-auto border rounded">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 sticky top-0">
                    <tr>
                        <th class="border px-3 py-2 text-left">Tanggal</th>
                        <th class="border px-3 py-2 text-left">Pelanggan</th>
                        <th class="border px-3 py-2 text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftarPemasukan as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-3 py-2">{{ $item->tgl_bayar->format('d/m/Y') }}</td>
                        <td class="border px-3 py-2">{{ $item->tagihan->pelanggan->nama }}</td>
                        <td class="border px-3 py-2 text-right font-medium text-green-600">
                            Rp {{ number_format($item->jml_bayar_input, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="border px-3 py-4 text-center text-gray-500">
                            Tidak ada data pemasukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tabel Pengeluaran --}}
    <div class="bg-white shadow rounded p-4">
        <h2 class="font-semibold mb-3 text-lg">Pengeluaran</h2>
        <div class="overflow-x-auto max-h-96 overflow-y-auto border rounded">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 sticky top-0">
                    <tr>
                        <th class="border px-3 py-2 text-left">Tanggal</th>
                        <th class="border px-3 py-2 text-left">Kategori</th>
                        <th class="border px-3 py-2 text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftarPengeluaran as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-3 py-2">{{ $item->tanggal_pengeluaran->format('d/m/Y') }}</td>
                        <td class="border px-3 py-2">{{ $item->kategori }}</td>
                        <td class="border px-3 py-2 text-right font-medium text-red-600">
                            Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="border px-3 py-4 text-center text-gray-500">
                            Tidak ada data pengeluaran
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection