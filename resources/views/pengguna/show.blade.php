@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Detail Pengguna</h1>
        <p class="text-gray-600">Informasi lengkap pengguna</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('pengguna.edit', $pengguna->id_pengguna) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>
        <button onclick="document.getElementById('modalResetPassword').classList.remove('hidden')"
                class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-key mr-2"></i>Reset Password
        </button>
        <a href="{{ route('pengguna.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Info Pengguna -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center mb-6">
                <div class="h-24 w-24 rounded-full bg-blue-500 flex items-center justify-center text-white text-4xl font-bold mb-4">
                    {{ strtoupper(substr($pengguna->nama, 0, 1)) }}
                </div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $pengguna->nama }}</h2>
                <p class="text-gray-600">{{ $pengguna->username }}</p>
                <span class="inline-block mt-2 px-4 py-1 rounded-full {{ $pengguna->role === 'Admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                    <i class="fas {{ $pengguna->role === 'Admin' ? 'fa-crown' : 'fa-user-tie' }} mr-1"></i>
                    {{ $pengguna->role }}
                </span>
            </div>

            <div class="space-y-3 border-t pt-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">Terdaftar:</span>
                    <span class="font-semibold">{{ $pengguna->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Bergabung:</span>
                    <span class="font-semibold">{{ $pengguna->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik Aktivitas</h3>
            <div class="space-y-4">
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Total Transaksi Pembayaran</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['total_transaksi'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Rp {{ number_format($stats['total_pembayaran'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-red-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Total Pengeluaran Dicatat</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['total_pengeluaran_dicatat'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Rp {{ number_format($stats['total_nilai_pengeluaran'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Transaksi Pembayaran Terbaru</h3>

            @if($transaksi_terbaru->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($transaksi_terbaru as $transaksi)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        {{ $transaksi->tgl_bayar->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $transaksi->tagihan->pelanggan->nama }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        {{ $transaksi->tagihan->periode }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-bold text-green-600">
                                        Rp {{ number_format($transaksi->jml_bayar_input, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-receipt text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-500">Belum ada transaksi</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Reset Password -->
<div id="modalResetPassword" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Reset Password</h3>
            <form method="POST" action="{{ route('pengguna.reset-password', $pengguna->id_pengguna) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                    <input type="password" name="new_password" class="w-full border rounded-lg px-4 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation" class="w-full border rounded-lg px-4 py-2" required>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                    <p class="text-xs text-yellow-800">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Password akan direset untuk user <strong>{{ $pengguna->username }}</strong>
                    </p>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg">
                        Reset Password
                    </button>
                    <button type="button" onclick="document.getElementById('modalResetPassword').classList.add('hidden')"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection