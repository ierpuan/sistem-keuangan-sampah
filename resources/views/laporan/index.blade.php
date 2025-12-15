@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="space-y-6">

    {{-- Filter & Export --}}
    <form method="GET" class="flex justify-between items-end gap-4">
        <div>
            <label class="block text-sm font-medium">Pilih Bulan</label>
            <input type="month" name="bulan" value="{{ $bulan }}"
                   class="border rounded px-3 py-2">
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 bg-gray-700 text-white rounded">
                Tampilkan
            </button>

            <a href="{{ route('laporan.export.pdf', ['bulan' => $bulan]) }}"
               class="px-4 py-2 bg-red-600 text-white rounded">
                Export PDF
            </a>

            <a href="{{ route('laporan.export.excel', ['bulan' => $bulan]) }}"
               class="px-4 py-2 bg-green-600 text-white rounded">
                Export Excel
            </a>
        </div>
    </form>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="p-4 bg-white shadow rounded">
            <p class="text-gray-500">Total Pemasukan</p>
            <p class="text-xl font-bold text-green-600">
                Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
            </p>
        </div>

        <div class="p-4 bg-white shadow rounded">
            <p class="text-gray-500">Total Pengeluaran</p>
            <p class="text-xl font-bold text-red-600">
                Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
            </p>
        </div>

        <div class="p-4 bg-white shadow rounded">
            <p class="text-gray-500">Saldo</p>
            <p class="text-xl font-bold">
                Rp {{ number_format($saldo, 0, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- Tabel Pemasukan --}}
    <div class="bg-white shadow rounded p-4">
        <h2 class="font-semibold mb-3">Pemasukan</h2>
        <table class="w-full text-sm border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-2 py-1">Tanggal</th>
                    <th class="border px-2 py-1">Pelanggan</th>
                    <th class="border px-2 py-1">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($daftarPemasukan as $item)
                <tr>
                    <td class="border px-2 py-1">{{ $item->tgl_bayar->format('d/m/Y') }}</td>
                    <td class="border px-2 py-1">{{ $item->tagihan->pelanggan->nama }}</td>
                    <td class="border px-2 py-1 text-right">
                        Rp {{ number_format($item->jml_bayar_input, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Tabel Pengeluaran --}}
    <div class="bg-white shadow rounded p-4">
        <h2 class="font-semibold mb-3">Pengeluaran</h2>
        <table class="w-full text-sm border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-2 py-1">Tanggal</th>
                    <th class="border px-2 py-1">Kategori</th>
                    <th class="border px-2 py-1">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($daftarPengeluaran as $item)
                <tr>
                    <td class="border px-2 py-1">{{ $item->tanggal_pengeluaran->format('d/m/Y') }}</td>
                    <td class="border px-2 py-1">{{ $item->kategori }}</td>
                    <td class="border px-2 py-1 text-right">
                        Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
