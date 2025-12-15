@extends('layouts.app')

@section('title', 'Edit Pengeluaran')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Edit Pengeluaran</h1>
    <p class="text-gray-600">Update data pengeluaran</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('pengeluaran.update', $pengeluaran->id_pengeluaran) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_pengeluaran" value="{{ old('tanggal_pengeluaran', $pengeluaran->tanggal_pengeluaran->format('Y-m-d')) }}"
                       class="w-full border rounded-lg px-4 py-2 @error('tanggal_pengeluaran') border-red-500 @enderror" required>
                @error('tanggal_pengeluaran')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="kategori" value="{{ old('kategori', $pengeluaran->kategori) }}"
                       class="w-full border rounded-lg px-4 py-2 @error('kategori') border-red-500 @enderror" required>
                @error('kategori')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah" value="{{ old('jumlah', $pengeluaran->jumlah) }}"
                       class="w-full border rounded-lg px-4 py-2 @error('jumlah') border-red-500 @enderror"
                       min="0" step="1000" required>
                @error('jumlah')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                <textarea name="keterangan" rows="3"
                          class="w-full border rounded-lg px-4 py-2 @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $pengeluaran->keterangan) }}</textarea>
                @error('keterangan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex gap-4">
            <button type="submit" class="bg-gray-300 hover:bg-gray-800 text-gray-800 hover:text-gray-300 px-6 py-2 rounded-lg">
                <i class="fas fa-save mr-2"></i>Update
            </button>
            <a href="{{ route('pengeluaran.index') }}" class="bg-gray-200 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
        </div>
    </form>
</div>
@endsection