@extends('layouts.app')

@section('title', 'Detail Tagihan')

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Detail Tagihan</h1>
    <nav class="flex text-xs text-gray-600 mt-2" aria-label="breadcrumb">
        <a href="{{ route('dashboard') }}" class="hover:text-gray-800">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('tagihan.index') }}" class="hover:text-gray-800">Tagihan</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">Detail</span>
    </nav>
</div>

<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-800 text-white px-4 py-4 rounded-t-lg">
        <div class="flex flex-col sm:flex-row items-start justify-between gap-4">
            <div class="flex-1">
                <h2 class="text-xl font-bold mb-2">{{ $tagihan->pelanggan->nama }}</h2>
                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-200">
                    <span>
                        <i class="fas fa-calendar mr-1"></i>
                        Periode: {{ \Carbon\Carbon::parse($tagihan->periode . '-01')->translatedFormat('F Y') }}
                    </span>
                    <span class="px-2 py-1 text-xs rounded-full {{ $tagihan->status === 'Lunas' ? 'bg-green-500 text-white' : ($tagihan->status === 'Tunggakan' ? 'bg-red-500 text-white' : 'bg-yellow-500 text-white') }}">
                        {{ $tagihan->status }}
                    </span>
                </div>
            </div>
            <div class="text-left sm:text-right w-full sm:w-auto">
                <p class="text-gray-300 text-xs mb-1">Sisa Tagihan</p>
                <p class="text-2xl font-bold">
                    Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Info Kiri -->
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat Pelanggan</label>
                    <p class="text-sm text-gray-800">{{ $tagihan->pelanggan->alamat_lengkap }}</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tagihan Pokok</label>
                    <p class="text-sm text-gray-800 font-bold">Rp {{ number_format($tagihan->jml_tagihan_pokok, 0, ',', '.') }}</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Total Terbayar</label>
                    <p class="text-sm text-green-600 font-bold">Rp {{ number_format($tagihan->total_sudah_bayar, 0, ',', '.') }}</p>
                </div>

                @if($tagihan->pelanggan->deposit)
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Saldo Deposit</label>
                    <p class="text-sm text-green-600 font-bold">Rp {{ number_format($tagihan->pelanggan->deposit->saldo_deposit, 0, ',', '.') }}</p>
                    @if($tagihan->pelanggan->deposit->saldo_deposit > 0 && !$tagihan->is_lunas)
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-info-circle"></i> Dapat digunakan untuk membayar
                        </p>
                    @endif
                </div>
                @endif
            </div>


            <!-- Info Kanan -->
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Status Tagihan</label>
                    <span class="inline-block px-2 py-1 text-xs rounded-full {{ $tagihan->status === 'Lunas' ? 'bg-green-100 text-green-800' : ($tagihan->status === 'Tunggakan' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ $tagihan->status }}
                    </span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Jatuh Tempo</label>
                    <p class="text-sm text-gray-800">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        {{ $tagihan->jatuh_tempo->translatedFormat('d F Y') }}
                    </p>
                    @if($tagihan->jatuh_tempo->isPast() && !$tagihan->is_lunas)
                        <p class="text-xs text-red-600 mt-1 font-medium">
                            <i class="fas fa-exclamation-triangle"></i> Sudah lewat {{ $tagihan->jatuh_tempo->diffForHumans() }}
                        </p>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Dibuat Pada</label>
                    <p class="text-sm text-gray-800">{{ $tagihan->created_at->translatedFormat('d F Y') }}</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Terakhir Diupdate</label>
                    <p class="text-sm text-gray-800">{{ $tagihan->updated_at->translatedFormat('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-200 pt-4 mb-4">
            <!-- Tombol Aksi -->
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('tagihan.index') }}"
                   class="text-center bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded transition text-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
                @if(!$tagihan->is_lunas)
                <a href="{{ route('pembayaran.create', $tagihan->id_tagihan) }}"
                   class="text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition text-sm">
                    <i class="fas fa-money-bill-wave mr-1"></i>Bayar Sekarang
                </a>
                @endif
                <a href="{{ route('pelanggan.show', $tagihan->pelanggan->id_pelanggan) }}"
                   class="text-center bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded transition text-sm">
                    <i class="fas fa-user mr-1"></i>Lihat Pelanggan
                </a>
            </div>
        </div>

        <!-- Riwayat Pembayaran -->
        <div class="border-t border-gray-200 pt-4">
            <h3 class="text-lg font-bold text-gray-800 mb-3">
                <i class="fas fa-history mr-2"></i>Riwayat Pembayaran
            </h3>

            @if($tagihan->transaksiPembayaran->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">No</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Jumlah</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Dari Deposit</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Petugas</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tagihan->transaksiPembayaran as $index => $transaksi)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        <i class="fas fa-calendar text-gray-400 mr-1"></i>
                                        {{ $transaksi->tgl_bayar->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-bold text-green-600">
                                        Rp {{ number_format($transaksi->jml_bayar_input, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($transaksi->jml_bayar_dari_deposit > 0)
                                            <span class="inline-flex items-center px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                                <i class="fas fa-wallet mr-1"></i>
                                                Rp {{ number_format($transaksi->jml_bayar_dari_deposit, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        <i class="fas fa-user text-gray-400 mr-1"></i>
                                        {{ $transaksi->pengguna->nama }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex gap-1 justify-center">
                                            <a href="{{ route('pembayaran.edit', $transaksi->id_transaksi) }}"
                                               class="bg-yellow-600 hover:bg-yellow-700 text-white px-2 py-1 rounded text-xs transition"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs transition"
                                                    title="Hapus"
                                                    onclick="confirmDelete({{ $transaksi->id_transaksi }})">
                                                <i class="fas fa-trash"></i>
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
                                <td colspan="2" class="px-4 py-3 text-right font-bold text-gray-700 text-sm">Total Pembayaran:</td>
                                <td class="px-4 py-3 font-bold text-green-600 text-sm">
                                    Rp {{ number_format($tagihan->total_sudah_bayar, 0, ',', '.') }}
                                </td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-8 bg-gray-50 rounded">
                    <i class="fas fa-file-invoice text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500 text-sm mb-3">Belum ada pembayaran untuk tagihan ini</p>
                </div>
            @endif
        </div>

        <!-- Riwayat Tagihan Pelanggan -->
        <div class="border-t border-gray-200 pt-4 mt-4">
            <h3 class="text-lg font-bold text-gray-800 mb-3">
                <i class="fas fa-list mr-2"></i>Riwayat Tagihan Pelanggan
            </h3>

            @if($tagihan->pelanggan->tagihan->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Periode</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Tagihan</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Terbayar</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Sisa</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Jatuh Tempo</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tagihan->pelanggan->tagihan as $riwayat)
                                <tr class="{{ $riwayat->id_tagihan == $tagihan->id_tagihan ? 'bg-yellow-50' : 'hover:bg-gray-50' }} transition-colors">
                                    <td class="px-4 py-3 text-sm {{ $riwayat->id_tagihan == $tagihan->id_tagihan ? 'font-bold' : '' }}">
                                        {{ \Carbon\Carbon::parse($riwayat->periode . '-01')->translatedFormat('F Y') }}
                                        @if($riwayat->id_tagihan == $tagihan->id_tagihan)
                                            <span class="ml-1 text-yellow-600"><i class="fas fa-arrow-left"></i></span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        Rp {{ number_format($riwayat->jml_tagihan_pokok, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-green-600 font-semibold">
                                        Rp {{ number_format($riwayat->total_sudah_bayar, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-red-600 font-semibold">
                                        Rp {{ number_format($riwayat->sisa_tagihan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $riwayat->status === 'Lunas' ? 'bg-green-100 text-green-800' : ($riwayat->status === 'Tunggakan' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ $riwayat->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $riwayat->jatuh_tempo->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($riwayat->status !== 'Lunas')
                                            <a href="{{ route('pembayaran.create', $riwayat->id_tagihan) }}"
                                               class="text-green-600 hover:text-green-700 text-xs font-medium">
                                                <i class="fas fa-money-bill-wave mr-1"></i>Bayar
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-gray-500 py-4 text-sm">Belum ada riwayat tagihan</p>
            @endif
        </div>
    </div>
</div>

<!-- JavaScript untuk Konfirmasi Hapus -->
<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus transaksi pembayaran ini?\n\nPerhatian:\n- Sisa tagihan akan bertambah setelah pembayaran dihapus\n- Jika pembayaran menggunakan deposit, saldo deposit akan dikembalikan')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection