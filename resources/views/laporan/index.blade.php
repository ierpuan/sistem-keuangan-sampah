@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Laporan Keuangan</h1>
    <p class="text-sm text-gray-600 mt-1">Laporan pemasukan dan pengeluaran</p>
</div>

{{-- Filter & Export --}}
<div class="bg-white rounded-lg shadow p-4 mb-4">
    <form method="GET" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Pilih Bulan / Tahun</label>
                <input type="month"
                       name="bulan"
                       value="{{ $bulan }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-0 focus:border-gray-500">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded transition text-sm">
                    <i class="fas fa-search mr-1"></i>Tampilkan
                </button>
                <a href="{{ url()->current() }}?bulan={{ date('Y-m') }}"
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded transition text-sm text-center">
                     <i class="fas fa-redo mr-1"></i>Reset
                 </a>
            </div>
        </div>

        <div class="flex sm:flex-row gap-2 pt-3 border-t border-gray-200">
            <a href="{{ route('laporan.export.pdf', ['bulan' => $bulan]) }}"
               class="text-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition text-sm">
                <i class="fas fa-file-pdf mr-1"></i>Export PDF
            </a>

            <a href="{{ route('laporan.export.excel', ['bulan' => $bulan]) }}"
               class="text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition text-sm">
                <i class="fas fa-file-excel mr-1"></i>Export Excel
            </a>
        </div>
    </form>
</div>

{{-- Ringkasan --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-700 text-xs font-medium mb-1">Total Pemasukan</p>
                <p class="text-xl font-bold text-gray-800">
                    Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-gray-100 p-3 rounded-lg">
                <i class="fas fa-arrow-down text-gray-700 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-700 text-xs font-medium mb-1">Total Pengeluaran</p>
                <p class="text-xl font-bold text-gray-800">
                    Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-gray-100 p-3 rounded-lg">
                <i class="fas fa-arrow-up text-gray-700 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-700 text-xs font-medium mb-1">Saldo</p>
                <p class="text-xl font-bold {{ $saldo >= 0 ? 'text-blue-700' : 'text-red-700' }}">
                    Rp {{ number_format($saldo, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-gray-100 p-3 rounded-lg">
                <i class="fas fa-wallet text-gray-700 text-xl"></i>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Pemasukan --}}
<div class="bg-white rounded-lg shadow overflow-hidden mb-4">
    <div class="bg-gray-100 text-gray-800 px-4 py-3">
        <h2 class="text-base font-bold flex items-center gap-2">
            <i class="fas fa-arrow-down"></i>
            Pemasukan
        </h2>
        <p class="text-xs text-gray-600 mt-1">Daftar pemasukan bulan ini</p>
    </div>
    <div class="overflow-x-auto">
        <div class="max-h-96 overflow-y-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Pelanggan</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($daftarPemasukan as $index => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            <i class="fas fa-calendar text-gray-400 mr-1"></i>
                            {{ $item->tgl_bayar->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $item->tagihan->pelanggan->nama }}</td>
                        <td class="px-4 py-3 text-sm text-right font-bold text-green-600">
                            Rp {{ number_format($item->jml_bayar_input, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p class="text-sm font-medium text-gray-500">Tidak ada data pemasukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($daftarPemasukan->count() > 0)
                <tfoot class="bg-gray-50 sticky bottom-0">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right text-sm font-bold text-gray-700">Total:</td>
                        <td class="px-4 py-3 text-right text-sm font-bold text-green-600">
                            Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

{{-- Tabel Pengeluaran --}}
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="bg-gray-100 text-gray-800 px-4 py-3">
        <h2 class="text-base font-bold flex items-center gap-2">
            <i class="fas fa-arrow-up"></i>
            Pengeluaran
        </h2>
        <p class="text-xs text-gray-600 mt-1">Daftar pengeluaran bulan ini</p>
    </div>
    <div class="overflow-x-auto">
        <div class="max-h-96 overflow-y-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Jenis</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($daftarPengeluaran as $index => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            <i class="fas fa-calendar text-gray-400 mr-1"></i>
                            {{ $item->tanggal_pengeluaran->translatedFormat('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $item->kategori }}</td>
                        <td class="px-4 py-3 text-sm text-right font-bold text-red-600">
                            Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p class="text-sm font-medium text-gray-500">Tidak ada data pengeluaran</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($daftarPengeluaran->count() > 0)
                <tfoot class="bg-gray-50 sticky bottom-0">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right text-sm font-bold text-gray-700">Total:</td>
                        <td class="px-4 py-3 text-right text-sm font-bold text-red-600">
                            Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection