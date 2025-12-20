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

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white px-4 py-4 rounded-t-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold mb-1">Edit Pembayaran</h2>
                    <p class="text-xs text-orange-100">Perbarui data pembayaran tagihan pelanggan</p>
                </div>
                <div class="hidden sm:block">
                    <i class="fas fa-edit text-4xl text-orange-300 opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="p-4">
            <!-- Error Messages -->
            @if ($errors->any())
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
            @endif

            <form action="{{ route('pembayaran.update', $transaksi->id_transaksi) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Informasi Transaksi -->
                <div class="mb-4">
                    <h3 class="text-sm font-bold text-gray-800 mb-2 flex items-center">
                        <i class="fas fa-receipt text-gray-500 mr-2"></i>
                        Informasi Transaksi
                    </h3>
                    <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                        <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-gray-200">
                            <div class="px-4 py-3">
                                <p class="text-xs text-gray-600 mb-1">ID Transaksi</p>
                                <p class="text-sm font-bold text-gray-800">{{ $transaksi->id_transaksi }}</p>
                            </div>
                            <div class="px-4 py-3">
                                <p class="text-xs text-gray-600 mb-1">Tanggal Bayar</p>
                                <p class="text-sm font-semibold text-gray-800">
                                    <i class="fas fa-calendar text-gray-400 mr-1"></i>
                                    {{ \Carbon\Carbon::parse($transaksi->tgl_bayar)->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div class="px-4 py-3">
                                <p class="text-xs text-gray-600 mb-1">Petugas</p>
                                <p class="text-sm font-semibold text-gray-800">
                                    <i class="fas fa-user text-gray-400 mr-1"></i>
                                    {{ $transaksi->pengguna->nama ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pelanggan & Tagihan -->
                <div class="mb-4">
                    <h3 class="text-sm font-bold text-gray-800 mb-2 flex items-center">
                        <i class="fas fa-file-invoice-dollar text-gray-500 mr-2"></i>
                        Informasi Tagihan
                    </h3>
                    <div class="bg-blue-50 rounded-lg border border-blue-200 p-4">
                        <!-- Pelanggan -->
                        <div class="mb-3 pb-3 border-b border-blue-200">
                            <p class="text-xs text-blue-700 font-semibold mb-1">Pelanggan</p>
                            <p class="text-base font-bold text-blue-900">{{ $transaksi->tagihan->pelanggan->nama }}</p>
                            <p class="text-xs text-blue-600 mt-1">Periode: {{ $transaksi->tagihan->periode }}</p>
                        </div>

                        <!-- Ringkasan Tagihan -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="bg-white rounded-lg p-3 border border-blue-200">
                                <p class="text-xs text-gray-600 mb-1">Tagihan Pokok</p>
                                <p class="text-lg font-bold text-gray-800">
                                    Rp {{ number_format($transaksi->tagihan->jml_tagihan_pokok, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-green-200">
                                <p class="text-xs text-gray-600 mb-1">Sudah Dibayar</p>
                                <p class="text-lg font-bold text-green-600">
                                    Rp {{ number_format($transaksi->tagihan->total_sudah_bayar, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-red-200">
                                <p class="text-xs text-gray-600 mb-1">Sisa Tagihan</p>
                                <p class="text-lg font-bold text-red-600">
                                    Rp {{ number_format($transaksi->tagihan->sisa_tagihan, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Input Pembayaran -->
                <div class="mb-4 bg-orange-50 rounded-lg border border-orange-200 p-4">
                    <h3 class="text-sm font-bold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-money-bill-wave text-orange-600 mr-2"></i>
                        Update Jumlah Pembayaran
                    </h3>

                    <div class="max-w-md">
                        <label class="block text-xs font-semibold text-gray-700 mb-2">
                            Jumlah Pembayaran Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-semibold">Rp</span>
                            <input type="number"
                                   name="jml_bayar_input"
                                   value="{{ old('jml_bayar_input', $transaksi->jml_bayar_input) }}"
                                   min="1"
                                   step="1000"
                                   required
                                   class="w-full border border-orange-300 rounded-lg px-3 py-2 pl-10 text-sm font-semibold focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                   placeholder="Masukkan jumlah pembayaran">
                        </div>

                        <!-- Info Pembayaran Sebelumnya -->
                        <div class="mt-3 bg-white rounded border border-orange-200 p-3">
                            <p class="text-xs text-gray-600 mb-1">Pembayaran Sebelumnya:</p>
                            <p class="text-sm font-bold text-gray-800">
                                Rp {{ number_format($transaksi->jml_bayar_input, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Catatan Penting -->
                <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-500 p-3 rounded">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5"></i>
                        <div class="flex-1 text-xs text-yellow-800">
                            <p class="font-semibold mb-2">Perhatian:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Perubahan jumlah pembayaran akan mempengaruhi sisa tagihan pelanggan</li>
                                <li>Jika pembayaran melebihi sisa tagihan, kelebihan akan masuk ke deposit pelanggan</li>
                                <li>Pastikan jumlah yang diinput sudah benar sebelum menyimpan</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex flex-wrap gap-2">
                        <button type="submit"
                                class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded text-sm transition flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                        <a href="{{ route('tagihan.show', $transaksi->tagihan->id_tagihan) }}"
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded text-sm transition flex items-center gap-2">
                            <i class="fas fa-times"></i>
                            <span>Batal</span>
                        </a>
                        <a href="{{ route('tagihan.index') }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm transition flex items-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali ke Daftar</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirmation Script -->
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const originalAmount = {{ $transaksi->jml_bayar_input }};
    const newAmount = parseInt(document.querySelector('input[name="jml_bayar_input"]').value);

    if (originalAmount !== newAmount) {
        const message = 'Anda akan mengubah jumlah pembayaran:\n\n' +
                       'Dari: Rp ' + originalAmount.toLocaleString('id-ID') + '\n' +
                       'Menjadi: Rp ' + newAmount.toLocaleString('id-ID') + '\n\n' +
                       'Lanjutkan?';

        if (!confirm(message)) {
            e.preventDefault();
        }
    }
});
</script>
@endsection