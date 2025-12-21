@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Header Section -->
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Dashboard</h1>
    <p class="text-xs text-gray-600 flex items-center gap-2">
        <svg class="w-3 h-3 text-gray-800" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
        </svg>
        Selamat datang, <span class="font-semibold text-gray-800">{{ Auth::user()->nama }}</span>
    </p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
    <!-- Total Pelanggan -->
    <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300 p-4">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-700 text-xs font-medium mb-1">Total Pelanggan Aktif</p>
                <p class="text-xl font-bold text-gray-800 mb-0.5">{{ $stats['total_pelanggan'] }}</p>
                <p class="text-xs text-gray-500">Pelanggan terdaftar</p>
            </div>
            <div class="bg-gray-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Tagihan Belum Lunas -->
    <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300 p-4">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-700 text-xs font-medium mb-1">Tagihan Belum Lunas</p>
                <p class="text-xl font-bold text-gray-800 mb-0.5">{{ $stats['tagihan_belum_lunas'] }}</p>
                <p class="text-xs text-gray-500">Butuh perhatian</p>
            </div>
            <div class="bg-gray-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Tunggakan -->
    <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300 p-4">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-700 text-xs font-medium mb-1">Total Belum Bayar/Tunggakan</p>
                <p class="text-xl font-bold text-gray-800 mb-0.5">Rp {{ number_format($stats['total_tunggakan'], 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500">Perlu ditagih</p>
            </div>
            <div class="bg-gray-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Pemasukan -->
    <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300 p-4">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-700 text-xs font-medium mb-1">Pemasukan Bulan Ini</p>
                <p class="text-xl font-bold text-gray-800 mb-0.5">Rp {{ number_format($stats['pemasukan_bulan_ini'], 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500">Income</p>
            </div>
            <div class="bg-gray-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Pengeluaran -->
    <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300 p-4">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-700 text-xs font-medium mb-1">Pengeluaran Bulan Ini</p>
                <p class="text-xl font-bold text-gray-800 mb-0.5">Rp {{ number_format($stats['pengeluaran_bulan_ini'], 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500">Expense</p>
            </div>
            <div class="bg-gray-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Saldo -->
    <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300 p-4">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-700 text-xs font-medium mb-1">Saldo Bulan Ini</p>
                <p class="text-xl font-bold text-gray-800 mb-0.5">Rp {{ number_format($stats['pemasukan_bulan_ini'] - $stats['pengeluaran_bulan_ini'], 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500">Balance</p>
            </div>
            <div class="bg-gray-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <!-- Transaksi Terbaru -->
    <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-700 to-gray-800 p-4">
            <h2 class="text-base font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
                Transaksi Terbaru
            </h2>
            <p class="text-gray-300 text-xs mt-1">5 transaksi terakhir</p>
        </div>
        <div class="p-4">
            @if($transaksi_terbaru->count() > 0)
                <div class="space-y-3">
                    @foreach($transaksi_terbaru as $transaksi)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-start gap-2">
                                <div class="bg-green-100 p-1.5 rounded-lg">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $transaksi->tagihan->pelanggan->nama }}</p>
                                    <p class="text-xs text-gray-600 mt-0.5">
                                        {{ $transaksi->tgl_bayar->format('d/m/Y H:i') }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $transaksi->tagihan->periode }} • {{ $transaksi->pengguna->nama }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-green-600">Rp {{ number_format($transaksi->jml_bayar_input, 0, ',', '.') }}</p>
                                @if($transaksi->jml_bayar_dari_deposit > 0)
                                    <p class="text-xs text-green-600 mt-1">
                                        <i class="fas fa-wallet"></i> Rp {{ number_format($transaksi->jml_bayar_dari_deposit, 0, ',', '.') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-gray-500 font-medium">Belum ada transaksi</p>
                    <p class="text-xs text-gray-400 mt-1">Transaksi akan muncul di sini</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Tagihan Jatuh Tempo -->
    <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-700 to-gray-800 p-4">
            <h2 class="text-base font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
                Tagihan Jatuh Tempo
            </h2>
            <p class="text-gray-300 text-xs mt-1">7 hari ke depan</p>
        </div>
        <div class="p-4">
            @if($tagihan_jatuh_tempo->count() > 0)
                <div class="space-y-3">
                    @foreach($tagihan_jatuh_tempo as $tagihan)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 border-l-4 border-gray-600">
                            <div class="flex items-start gap-2">
                                <div class="bg-gray-200 p-1.5 rounded-lg">
                                    <svg class="w-4 h-4 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $tagihan->pelanggan->nama }}</p>
                                    <p class="text-xs text-gray-600 mt-0.5">Periode: {{ $tagihan->periode }}</p>
                                    <p class="text-xs text-gray-600 font-medium mt-0.5">
                                        {{ $tagihan->jatuh_tempo->isPast() ? 'Lewat' : 'Jatuh tempo' }}: {{ $tagihan->jatuh_tempo->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-800 mb-1">Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}</p>
                                <span class="text-xs px-2 py-1 rounded-full font-medium bg-gray-200 text-gray-700">
                                    {{ $tagihan->status }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-gray-500 font-medium">Tidak ada tagihan jatuh tempo</p>
                    <p class="text-xs text-gray-400 mt-1">Semua tagihan dalam kondisi baik</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection