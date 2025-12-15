@extends('layouts.app')

@section('title', 'Pembayaran Tagihan')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Pembayaran Tagihan</h1>
    <p class="text-gray-600">Catat pembayaran pelanggan</p>
</div>

    <!-- Form Pembayaran -->
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Form Pembayaran</h2>

    <form method="POST" action="{{ route('pembayaran.store') }}">
        @csrf
        <input type="hidden" name="id_tagihan" value="{{ $tagihan->id_tagihan }}">

        <!-- Info Pelanggan -->
        <div class="mb-4 text-sm text-gray-700">
            <p><strong>Pelanggan:</strong> {{ $tagihan->pelanggan->nama }}</p>
        </div>

        <!-- Total Tagihan -->
        <div class="mb-4 text-sm text-gray-700">
            <p>
                <strong>Total Kewajiban Bayar</strong><br>
                Rp. {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}
            </p>
        </div>

        <!-- Input Pembayaran -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Jumlah Pembayaran <span class="text-red-500">*</span>
                <p> <small class="text-gray-500"> input nominal tanpa tanda titik (.) atau koma (,) </small> </p>
            </label>
                   <input type="number" name="jml_bayar_input" id="jml_bayar_input"
                   class="w-80 rounded px-2 py-1"
                   placeholder="contoh: 10000"
                   value="{{ old('jml_bayar_input') }}"
                   min="0">

        </div>

        <!-- Info Deposit -->
        @if($tagihan->pelanggan->deposit)
            <div class="text-sm text-gray-700 mb-2">
                <p>
                    <strong>Saldo deposit:</strong>
                    Rp. {{ number_format($tagihan->pelanggan->deposit->saldo_deposit, 0, ',', '.') }}
                </p>

                <label class="flex items-center mt-2 text-sm">
                    <input type="checkbox" name="gunakan_deposit" value="1" class="mr-2"
                           {{ old('gunakan_deposit') ? 'checked' : '' }}>
                    Gunakan saldo untuk membayar
                </label>

            </div>
        @endif

        <!-- Tombol -->
        <div class="flex justify-end gap-2 mt-6">
            <a href="{{ route('tagihan.index', $tagihan->id_tagihan) }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-5 py-2 rounded">
                Batal
            </a>
            <button type="submit"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded">
                Bayar
            </button>
        </div>
    </form>
</div>

@endsection