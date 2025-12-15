@extends('layouts.app')

@section('title', 'Edit Pembayaran')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Edit Pembayaran</h1>
    <nav class="flex text-sm text-gray-600 mt-2" aria-label="breadcrumb">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('tagihan.index') }}" class="hover:text-blue-600">Tagihan</a>
        <span class="mx-2">/</span>
        <a href="{{ route('tagihan.show', $transaksi->tagihan->id_tagihan) }}" class="hover:text-blue-600">Detail Tagihan</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">Edit Pembayaran</span>
    </nav>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Edit Pembayaran -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow">
            <div class="bg-blue-600 text-white px-6 py-4 rounded-t-lg">
                <h2 class="text-xl font-bold">Form Edit Pembayaran</h2>
            </div>
            <div class="p-6">
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('pembayaran.update', $transaksi->id_transaksi) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Transaksi -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-800 mb-2">Informasi Transaksi</label>
                        <div class="border border-gray-200 rounded overflow-hidden">
                            <table class="w-full text-sm">
                                <tr class="border-b border-gray-200">
                                    <td class="px-4 py-2 bg-gray-50 font-semibold w-48">ID Transaksi</td>
                                    <td class="px-4 py-2">{{ $transaksi->id_transaksi }}</td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <td class="px-4 py-2 bg-gray-50 font-semibold">Tanggal Bayar</td>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($transaksi->tgl_bayar)->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 bg-gray-50 font-semibold">Petugas</td>
                                    <td class="px-4 py-2">{{ $transaksi->pengguna->name ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Informasi Tagihan -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-800 mb-2">Informasi Tagihan</label>
                        <div class="border border-gray-200 rounded overflow-hidden">
                            <table class="w-full text-sm">
                                <tr class="border-b border-gray-200">
                                    <td class="px-4 py-2 bg-gray-50 font-semibold w-48">Pelanggan</td>
                                    <td class="px-4 py-2">{{ $transaksi->tagihan->pelanggan->nama_pelanggan }}</td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <td class="px-4 py-2 bg-gray-50 font-semibold">Total Tagihan</td>
                                    <td class="px-4 py-2">Rp {{ number_format($transaksi->tagihan->total_tagihan, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <td class="px-4 py-2 bg-gray-50 font-semibold">Sudah Dibayar</td>
                                    <td class="px-4 py-2">Rp {{ number_format($transaksi->tagihan->total_dibayar, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 bg-gray-50 font-semibold">Sisa Tagihan</td>
                                    <td class="px-4 py-2 text-red-600 font-bold">Rp {{ number_format($transaksi->tagihan->sisa_tagihan, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Input Pembayaran -->
                    <div class="mb-6">
                        <label for="jml_bayar_input" class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah Pembayaran <span class="text-red-500">*</span>
                            <p><small class="text-gray-500">Input nominal tanpa tanda titik (.) atau koma (,)</small></p>
                        </label>
                        <input
                            type="number"
                            class="w-80 rounded px-3 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jml_bayar_input') border-red-500 @enderror"
                            id="jml_bayar_input"
                            name="jml_bayar_input"
                            value="{{ old('jml_bayar_input', $transaksi->jml_bayar_input) }}"
                            placeholder="contoh: 10000"
                            min="1"
                            required>
                        <small class="block text-gray-500 mt-1">Jumlah pembayaran saat ini: Rp {{ number_format($transaksi->jml_bayar_input, 0, ',', '.') }}</small>
                        @error('jml_bayar_input')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Catatan -->
                    <div class="bg-blue-50 border border-blue-200 rounded px-4 py-3 mb-6">
                        <strong class="text-blue-800">📌 Catatan:</strong>
                        <ul class="list-disc list-inside text-sm text-blue-700 mt-2 mb-0">
                            <li>Jika jumlah pembayaran melebihi sisa tagihan, kelebihan akan masuk ke deposit pelanggan</li>
                            <li>Pastikan jumlah pembayaran sudah benar sebelum menyimpan</li>
                        </ul>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('tagihan.show', $transaksi->tagihan->id_tagihan) }}"
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-5 py-2 rounded">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <!-- Riwayat Pembayaran -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="bg-cyan-600 text-white px-6 py-4 rounded-t-lg">
                <h2 class="text-lg font-bold">Riwayat Pembayaran</h2>
            </div>
            <div class="p-4">
                @php
                    $riwayat = \App\Models\TransaksiPembayaran::where('id_tagihan', $transaksi->tagihan->id_tagihan)
                                ->orderBy('tgl_bayar', 'desc')
                                ->get();
                @endphp

                @if($riwayat->count() > 0)
                    <div class="space-y-2">
                        @foreach($riwayat as $item)
                            <div class="border border-gray-200 rounded p-3 {{ $item->id_transaksi == $transaksi->id_transaksi ? 'bg-yellow-50 border-yellow-300' : '' }}">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <small class="text-gray-500 block">{{ \Carbon\Carbon::parse($item->tgl_bayar)->format('d/m/Y H:i') }}</small>
                                        <strong class="text-blue-600">Rp {{ number_format($item->jml_bayar_input, 0, ',', '.') }}</strong>
                                    </div>
                                    @if($item->id_transaksi == $transaksi->id_transaksi)
                                        <span class="bg-yellow-400 text-yellow-900 text-xs px-2 py-1 rounded">Sedang Diedit</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center text-sm mb-0">Belum ada riwayat pembayaran</p>
                @endif
            </div>
        </div>

        <!-- Saldo Deposit -->
        @if($transaksi->tagihan->pelanggan->deposit)
        <div class="bg-white rounded-lg shadow">
            <div class="bg-green-600 text-white px-6 py-4 rounded-t-lg">
                <h2 class="text-lg font-bold">Saldo Deposit</h2>
            </div>
            <div class="p-6 text-center">
                <h3 class="text-3xl font-bold text-green-600 mb-1">
                    Rp {{ number_format($transaksi->tagihan->pelanggan->deposit->saldo_deposit, 0, ',', '.') }}
                </h3>
                <small class="text-gray-500">Saldo tersedia</small>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection