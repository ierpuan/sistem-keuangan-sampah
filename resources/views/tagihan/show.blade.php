@extends('layouts.app')

@section('title', 'Detail Tagihan')

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Detail Tagihan</h1>
            <p class="text-gray-600">Informasi lengkap tagihan pelanggan</p>
        </div>
        <div>
            <a href="{{ route('tagihan.index') }}"
               class="inline-flex items-center gap-2 bg-gray-700 hover:bg-gray-800 text-white px-6 py-3 rounded-xl transition-colors duration-200 shadow-lg">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>
</div>

<!-- Alert Messages -->
@if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 flex items-start gap-3 shadow-sm">
        <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 flex items-start gap-3 shadow-sm">
        <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span class="font-medium">{{ session('error') }}</span>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Info Tagihan -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
                Informasi Tagihan
            </h2>

            <div class="space-y-5">
                <!-- Pelanggan -->
                <div class="pb-4 border-b border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Pelanggan</p>
                    <p class="font-bold text-gray-800 text-lg">{{ $tagihan->pelanggan->nama }}</p>
                    <p class="text-sm text-gray-600 mt-1 flex items-start gap-2">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        {{ $tagihan->pelanggan->alamat_lengkap }}
                    </p>
                </div>

                <!-- Periode -->
                <div class="pb-4 border-b border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Periode Tagihan</p>
                    <p class="font-bold text-3xl text-gray-800">{{ $tagihan->periode }}</p>
                </div>

                <!-- Tagihan Pokok -->
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Tagihan Pokok</p>
                    <p class="font-bold text-2xl text-gray-800">Rp {{ number_format($tagihan->jml_tagihan_pokok, 0, ',', '.') }}</p>
                </div>

                <!-- Total Terbayar -->
                <div class="bg-green-50 rounded-xl p-4">
                    <p class="text-xs text-green-600 uppercase tracking-wide mb-1">Total Terbayar</p>
                    <p class="font-bold text-2xl text-green-600">Rp {{ number_format($tagihan->total_sudah_bayar, 0, ',', '.') }}</p>
                </div>

                <!-- Sisa Tagihan -->
                <div class="bg-gradient-to-br from-gray-700 to-gray-800 rounded-xl p-4 text-white">
                    <p class="text-xs text-gray-300 uppercase tracking-wide mb-1">Sisa Tagihan</p>
                    <p class="font-bold text-3xl">Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}</p>
                </div>

                <!-- Info Deposit -->
                @if($tagihan->pelanggan->deposit)
                <div class="bg-green-50 rounded-xl p-4 border-2 border-green-200">
                    <div class="flex items-start gap-3">
                        <div class="bg-green-100 p-2 rounded-lg">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-green-600 uppercase tracking-wide mb-1">Saldo Deposit</p>
                            <p class="font-bold text-xl text-green-700">Rp {{ number_format($tagihan->pelanggan->deposit->saldo_deposit, 0, ',', '.') }}</p>
                            @if($tagihan->pelanggan->deposit->saldo_deposit > 0 && !$tagihan->is_lunas)
                                <p class="text-xs text-green-600 mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    Dapat digunakan untuk membayar
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Jatuh Tempo -->
                <div class="pb-4 border-b border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Jatuh Tempo</p>
                    <p class="font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        {{ $tagihan->jatuh_tempo->format('d F Y') }}
                    </p>
                    @if($tagihan->jatuh_tempo->isPast() && !$tagihan->is_lunas)
                        <p class="text-xs text-red-600 mt-2 flex items-center gap-1 font-medium">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Sudah lewat {{ $tagihan->jatuh_tempo->diffForHumans() }}
                        </p>
                    @endif
                </div>

                <!-- Status -->
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Status Tagihan</p>
                    <span class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-full
                        {{ $tagihan->status === 'Lunas' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $tagihan->status === 'Tunggakan' ? 'bg-gray-200 text-gray-700' : '' }}
                        {{ $tagihan->status === 'Belum Bayar' ? 'bg-gray-200 text-gray-700' : '' }}">
                        {{ $tagihan->status }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Pembayaran -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gray-800 p-6">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    Riwayat Pembayaran
                </h2>
                <p class="text-gray-300 text-sm mt-1">Daftar transaksi pembayaran tagihan ini</p>
            </div>

            <div class="p-6">
                @if($tagihan->transaksiPembayaran->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Petugas</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tagihan->transaksiPembayaran as $index => $transaksi)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $transaksi->tgl_bayar->format('d/m/Y H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                            Rp {{ number_format($transaksi->jml_bayar_input, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $transaksi->pengguna->nama }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                            <div class="flex gap-2 justify-center">
                                                <!-- Tombol Edit -->
                                                <a href="{{ route('pembayaran.edit', $transaksi->id_transaksi) }}"
                                                   class="inline-flex items-center gap-1 bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-xs font-medium transition-colors duration-200"
                                                   title="Edit Pembayaran">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                    </svg>
                                                    Edit
                                                </a>

                                                <!-- Tombol Hapus -->
                                                <button type="button"
                                                        class="inline-flex items-center gap-1 bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded-lg text-xs font-medium transition-colors duration-200"
                                                        title="Hapus Pembayaran"
                                                        onclick="confirmDelete({{ $transaksi->id_transaksi }})">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Hapus
                                                </button>

                                                <form id="delete-form-{{ $transaksi->id_transaksi }}"
                                                      action="{{ route('pembayaran.destroy', $transaksi->id_transaksi) }}"
                                                      method="POST"
                                                      style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-right font-bold text-gray-700 text-base">Total Pembayaran:</td>
                                    <td class="px-6 py-4 font-bold text-green-600 text-base">
                                        Rp {{ number_format($tagihan->total_sudah_bayar, 0, ',', '.') }}
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium text-lg mb-2">Belum ada pembayaran</p>
                        <p class="text-gray-400 text-sm mb-6">Pembayaran akan muncul di sini setelah transaksi dilakukan</p>
                        @if(!$tagihan->is_lunas)
                            <a href="{{ route('pembayaran.create', $tagihan->id_tagihan) }}"
                               class="inline-flex items-center gap-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                </svg>
                                Bayar Sekarang
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

     <!-- Riwayat Tagihan -->
<div class="bg-white rounded-lg shadow p-6 mt-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Riwayat Tagihan</h2>

    @if($tagihan->pelanggan->tagihan->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tagihan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Terbayar</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sisa</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($tagihan->pelanggan->tagihan as $riwayat)
                        <tr class="{{ $riwayat->id_tagihan == $tagihan->id_tagihan ? 'bg-gray-100 font-semibold' : '' }}">
                            <td class="px-4 py-3 text-sm">{{ $riwayat->periode }}</td>
                            <td class="px-4 py-3 text-sm">
                                Rp {{ number_format($riwayat->jml_tagihan_pokok,0,',','.') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-green-600">
                                Rp {{ number_format($riwayat->total_sudah_bayar,0,',','.') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-red-600">
                                Rp {{ number_format($riwayat->sisa_tagihan,0,',','.') }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $riwayat->status === 'Lunas' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $riwayat->status === 'Tunggakan' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $riwayat->status === 'Belum Bayar' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    {{ $riwayat->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $riwayat->jatuh_tempo->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm">
                                @if($riwayat->status !== 'Lunas')
                                    <a href="{{ route('pembayaran.create', $riwayat->id_tagihan) }}" class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-money-bill-wave"></i> Bayar
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-center text-gray-500 py-6">Belum ada riwayat tagihan</p>
    @endif
</div>
    </div>
</div>

<!-- JavaScript untuk Konfirmasi Hapus -->
<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus transaksi pembayaran ini?\n\nPerhatian: Sisa tagihan akan bertambah setelah pembayaran dihapus.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection