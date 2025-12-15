@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">
                Dashboard
            </h1>
            <p class="text-gray-600 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-800" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
                Selamat datang, <span class="font-semibold text-gray-800">{{ Auth::user()->nama }}</span>
            </p>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Total Pelanggan -->
    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 text-gray-700">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-700 text-sm font-medium mb-1">Total Pelanggan Aktif</p>
                <p class="text-4xl font-bold text-gray-600 mb-1">{{ $stats['total_pelanggan'] }}</p>
                <p class="text-xs text-gray-700">Pelanggan terdaftar</p>
            </div>
            <div class="bg-gray-400 bg-opacity-50 p-4 rounded-2xl shadow-lg">
                <svg class="w-8 h-8 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Tagihan Belum Lunas -->
    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 text-gray-700">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-700 text-sm font-medium mb-1">Tagihan Belum Lunas</p>
                <p class="text-4xl font-bold text-gray-700 mb-1">{{ $stats['tagihan_belum_lunas'] }}</p>
                <p class="text-xs text-gray-700">Butuh perhatian</p>
            </div>
            <div class="bg-gray-400 bg-opacity-50 p-4 rounded-2xl shadow-lg">
                <svg class="w-8 h-8 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Tunggakan -->
    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 text-gray-700">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-700 text-sm font-medium mb-1">Total Belum Bayar/Tunggakan</p>
                <p class="text-3xl font-bold text-gray-700 mb-1">Rp {{ number_format($stats['total_tunggakan'], 0, ',', '.') }}</p>
                <p class="text-xs text-gray-700">Perlu ditagih</p>
            </div>
            <div class="bg-gray-400 bg-opacity-50 p-4 rounded-2xl shadow-lg">
                <svg class="w-8 h-8 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Pemasukan -->
    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 text-gray-700">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-700 text-sm font-medium mb-1">Pemasukan Bulan Ini</p>
                <p class="text-3xl font-bold mb-1">Rp {{ number_format($stats['pemasukan_bulan_ini'], 0, ',', '.') }}</p>
                <p class="text-xs text-gray-700 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                    </svg>
                    Income
                </p>
            </div>
            <div class="bg-gray-400 bg-opacity-50 backdrop-blur-sm p-4 rounded-2xl">
                <svg class="w-8 h-8 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Pengeluaran -->
    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 text-gray-700">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-700 text-sm font-medium mb-1">Pengeluaran Bulan Ini</p>
                <p class="text-3xl font-bold mb-1">Rp {{ number_format($stats['pengeluaran_bulan_ini'], 0, ',', '.') }}</p>
                <p class="text-xs text-gray-700 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12 13a1 1 0 100 2h5a1 1 0 001-1V9a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586 3.707 5.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z" clip-rule="evenodd"/>
                    </svg>
                    Expense
                </p>
            </div>
            <div class="bg-gray-400 bg-opacity-50 backdrop-blur-sm p-4 rounded-2xl">
                <svg class="w-8 h-8 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Saldo -->
    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 text-gray-700">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-700 text-sm font-medium mb-1">Saldo Bulan Ini</p>
                <p class="text-3xl font-bold mb-1">Rp {{ number_format($stats['pemasukan_bulan_ini'] - $stats['pengeluaran_bulan_ini'], 0, ',', '.') }}</p>
                <p class="text-xs text-gray-700 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                    </svg>
                    Balance
                </p>
            </div>
            <div class="bg-gray-400 bg-opacity-50 backdrop-blur-sm p-4 rounded-2xl">
                <svg class="w-8 h-8 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Transaksi Terbaru -->
    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
        <div class="bg-gray-800 p-6">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
                Transaksi Terbaru
            </h2>
            <p class="text-gray-300 text-sm mt-1">5 transaksi terakhir</p>
        </div>
        <div class="p-6">
            @if($transaksi_terbaru->count() > 0)
                <div class="space-y-4">
                    @foreach($transaksi_terbaru as $transaksi)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-start gap-3">
                                <div class="bg-green-100 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $transaksi->tagihan->pelanggan->nama }}</p>
                                    <p class="text-sm text-gray-600 flex items-center gap-1 mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $transaksi->tgl_bayar->format('d/m/Y H:i') }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">Periode: {{ $transaksi->tagihan->periode }} • {{ $transaksi->pengguna->nama }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-600 text-lg">Rp {{ number_format($transaksi->jml_bayar_input, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-gray-500 font-medium">Belum ada transaksi</p>
                    <p class="text-gray-400 text-sm mt-1">Transaksi akan muncul di sini</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Tagihan Jatuh Tempo -->
    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
        <div class="bg-gray-800 p-6">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
                Tagihan Jatuh Tempo
            </h2>
            <p class="text-gray-300 text-sm mt-1">7 hari ke depan</p>
        </div>
        <div class="p-6">
            @if($tagihan_jatuh_tempo->count() > 0)
                <div class="space-y-4">
                    @foreach($tagihan_jatuh_tempo as $tagihan)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200 border-l-4 border-gray-600">
                            <div class="flex items-start gap-3">
                                <div class="bg-gray-200 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $tagihan->pelanggan->nama }}</p>
                                    <p class="text-sm text-gray-600 mt-1">Periode: {{ $tagihan->periode }}</p>
                                    <p class="text-xs text-gray-600 font-medium mt-1 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $tagihan->jatuh_tempo->isPast() ? 'Lewat jatuh tempo' : 'Jatuh tempo' }}: {{ $tagihan->jatuh_tempo->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800 text-lg mb-2">Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}</p>
                                <span class="text-xs px-3 py-1.5 rounded-full font-medium bg-gray-200 text-gray-700">
                                    {{ $tagihan->status }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-gray-500 font-medium">Tidak ada tagihan jatuh tempo</p>
                    <p class="text-gray-400 text-sm mt-1">Semua tagihan dalam kondisi baik</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection