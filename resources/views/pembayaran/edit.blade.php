@extends('layouts.app')

@section('title', 'Edit Pembayaran')

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Edit Pembayaran</h1>
    <nav class="flex text-xs text-gray-600 mt-2" aria-label="breadcrumb">
        <a href="{{ route('dashboard') }}" class="hover:text-gray-800">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('tagihan.index') }}" class="hover:text-gray-800">Tagihan</a>
        <span class="mx-2">/</span>
        <a href="{{ route('tagihan.show', $transaksi->tagihan->id_tagihan) }}" class="hover:text-gray-800">Detail Tagihan</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">Edit Pembayaran</span>
    </nav>
</div>

<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-800 text-white px-4 py-3 rounded-t-lg">
        <h2 class="text-lg font-bold">Form Edit Pembayaran</h2>
        <p class="text-xs text-gray-300 mt-1">Perbarui data pembayaran tagihan pelanggan</p>
    </div>

    <!-- Body -->
    <div class="p-4">
        <!-- Error Messages -->
        {{-- @if ($errors->any())
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-3 rounded">
                <div class="flex items-start gap-2">
                    <i class="fas fa-exclamation-circle text-red-600 mt-0.5"></i>
                    <div class="flex-1">
                        <p class="text-xs font-semibold text-red-800 mb-1">Terdapat kesalahan:</p>
                        <ul class="list-disc list-inside text-xs text-red-700 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif --}}

        <form action="{{ route('pembayaran.update', $transaksi->id_transaksi) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Info Kiri -->
                <div class="space-y-3">
                    <!-- Info Pelanggan -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Pelanggan</label>
                        <p class="text-sm text-gray-800 font-medium">{{ $transaksi->tagihan->pelanggan->nama }}</p>
                    </div>

                    <!-- Periode -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Periode Tagihan</label>
                        <p class="text-sm text-gray-800">{{ \Carbon\Carbon::parse($transaksi->tagihan->periode . '-01')->translatedFormat('F Y') }}</p>
                    </div>

                    <!-- ID Transaksi -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">ID Transaksi</label>
                        <p class="text-sm text-gray-800 font-medium">{{ $transaksi->id_transaksi }}</p>
                    </div>

                    <!-- Tanggal Bayar -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Bayar</label>
                        <p class="text-sm text-gray-800">{{ \Carbon\Carbon::parse($transaksi->tgl_bayar)->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <!-- Info Kanan -->
                <div class="space-y-3">
                    <!-- Total Tagihan -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Total Tagihan</label>
                        <p class="text-base text-gray-800 font-bold">Rp {{ number_format($transaksi->tagihan->jml_tagihan_pokok, 0, ',', '.') }}</p>
                    </div>

                    <!-- Sisa Tagihan -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Sisa Tagihan</label>
                        <p class="text-base text-red-600 font-bold">Rp {{ number_format($transaksi->tagihan->sisa_tagihan, 0, ',', '.') }}</p>
                    </div>

                    <!-- Pembayaran Sebelumnya -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-3">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Pembayaran Sebelumnya</label>
                        <p class="text-sm text-gray-800 font-bold">Rp {{ number_format($transaksi->jml_bayar_input, 0, ',', '.') }}</p>
                    </div>

                    <!-- Input Pembayaran Baru -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Jumlah Pembayaran Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                            name="jml_bayar_input"
                            value="{{ old('jml_bayar_input', number_format($transaksi->jml_bayar_input ?? 0, 0, '', '')) }}"
                            min="0"
                            step="1"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-0 focus:border-gray-500 @error('jml_bayar_input') border-red-500 @enderror"
                            placeholder="Masukkan jumlah pembayaran">
                        @error('jml_bayar_input')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Masukkan jumlah dalam Rupiah (misal: 50000 untuk Rp 50.000)</p>
                    </div>

                    <!-- Info Deposit -->
                    @if($transaksi->tagihan->pelanggan->deposit)
                    <div class="bg-green-50 border border-green-200 rounded p-3">
                        <label class="block text-xs font-medium text-green-700 mb-1">Saldo Deposit</label>
                        <p class="text-sm text-green-800 font-bold mb-2">
                            Rp {{ number_format($transaksi->tagihan->pelanggan->deposit->saldo_deposit, 0, ',', '.') }}
                        </p>
                        @if($transaksi->tagihan->pelanggan->deposit->saldo_deposit > 0)
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

            <!-- Catatan -->
            <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-500 p-3 rounded">
                <div class="flex items-start gap-2">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5"></i>
                    <div class="flex-1 text-xs text-yellow-800">
                        <p class="font-semibold mb-1">Perhatian:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            <li>Perubahan jumlah pembayaran akan mempengaruhi sisa tagihan pelanggan</li>
                            <li>Jika pembayaran melebihi sisa tagihan, kelebihan akan masuk ke deposit pelanggan</li>
                            <li>Pastikan jumlah yang diinput sudah benar sebelum menyimpan</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-200 pt-4">
                <!-- Tombol Aksi -->
                <div class="flex sm:flex-row gap-2">
                    <button type="submit"
                            class="text-center bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm transition">
                        <i class="fas fa-save mr-1"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('tagihan.show', $transaksi->tagihan->id_tagihan) }}"
                       class="text-center bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded text-sm transition">
                        <i class="fas fa-times mr-1"></i>Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Confirmation Script -->
{{-- <script>
const checkboxDeposit = document.querySelector('input[name="gunakan_deposit"]');
const inputBayar = document.querySelector('input[name="jml_bayar_input"]');

if (checkboxDeposit) {
    function toggleInput() {
        if (checkboxDeposit.checked) {
            inputBayar.value = '';
            inputBayar.disabled = true;
            inputBayar.classList.add('bg-gray-100');
        } else {
            inputBayar.disabled = false;
            inputBayar.classList.remove('bg-gray-100');
        }
    }

    checkboxDeposit.addEventListener('change', toggleInput);
    toggleInput(); // jalankan saat halaman pertama kali load
}
</script> --}}

@endsection