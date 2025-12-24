@extends('layouts.app')

@section('title', 'Pembayaran Tagihan')

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Pembayaran Tagihan</h1>
    <nav class="flex text-xs text-gray-600 mt-2" aria-label="breadcrumb">
        <a href="{{ route('dashboard') }}" class="hover:text-gray-800">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('tagihan.index') }}" class="hover:text-gray-800">Tagihan</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">Pembayaran</span>
    </nav>
</div>

<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-800 text-white px-4 py-3 rounded-t-lg">
        <h2 class="text-lg font-bold">Form Pembayaran</h2>
        <p class="text-xs text-gray-300 mt-1">Catat pembayaran pelanggan</p>
    </div>

    <!-- Content -->
    <div class="p-4">
        <form method="POST" action="{{ route('pembayaran.store') }}">
            @csrf
            <input type="hidden" name="id_tagihan" value="{{ $tagihan->id_tagihan }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Info Kiri -->
                <div class="space-y-3">
                    <!-- Info Pelanggan -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Pelanggan</label>
                        <p class="text-sm text-gray-800 font-medium">{{ $tagihan->pelanggan->nama }}</p>
                    </div>

                    <!-- Periode -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Periode Tagihan</label>
                        <p class="text-sm text-gray-800">{{ \Carbon\Carbon::parse($tagihan->periode . '-01')->translatedFormat('F Y') }}</p>
                    </div>

                    <!-- Total Tagihan -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Total Kewajiban Bayar</label>
                        <p class="text-base text-gray-800 font-bold">Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Info Kanan -->
                <div class="space-y-3">
                    <!-- Input Pembayaran -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Jumlah Pembayaran
                    </label>
                    <input type="number"
                        name="jml_bayar_input"
                        id="jml_bayar_input"
                        class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-0 focus:border-gray-500 @error('jml_bayar_input') border-red-500 @enderror"
                        placeholder="contoh: 10000"
                        value="{{ old('jml_bayar_input') }}"
                        min="0"
                        step="1">
                    <p class="text-xs text-gray-500 mt-1">Input nominal tanpa tanda titik (.)</p>
                    @error('jml_bayar_input')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                    <!-- Info Deposit -->
                    @if($tagihan->pelanggan->deposit)
                    <div class="bg-green-50 border border-green-200 rounded p-3">
                        <label class="block text-xs font-medium text-green-700 mb-1">Saldo Deposit</label>
                        <p class="text-sm text-green-800 font-bold mb-2">
                            Rp {{ number_format($tagihan->pelanggan->deposit->saldo_deposit, 0, ',', '.') }}
                        </p>
                        @if($tagihan->pelanggan->deposit->saldo_deposit > 0)
                        <label class="flex items-center text-xs text-gray-700">
                            <input type="checkbox"
                                   name="gunakan_deposit"
                                   value="1"
                                   class="mr-2 rounded text-green-600 focus:ring-green-500"
                                   {{ old('gunakan_deposit') ? 'checked' : '' }}>
                            <span class="font-medium">Gunakan saldo untuk membayar</span>
                        </label>
                        @else
                        <p class="text-xs text-gray-500 italic">Saldo deposit tidak tersedia</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-200 pt-4">
                <!-- Tombol Aksi -->
                <div class="flex sm:flex-row gap-2">
                    <a href="{{ route('tagihan.show', $tagihan->id_tagihan) }}"
                       class="text-center bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded transition text-sm">
                        <i class="fas fa-arrow-left mr-1"></i>Batal
                    </a>
                    <button type="submit"
                            class="text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition text-sm">
                        <i class="fas fa-check mr-1"></i>Bayar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection