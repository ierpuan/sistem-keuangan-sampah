@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Kelola Pengguna</h1>
        <p class="text-gray-600">Manajemen akun pengguna sistem</p>
    </div>
    <a href="{{ route('pengguna.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
        <i class="fas fa-user-plus mr-2"></i>Tambah Pengguna
    </a>
</div>

<!-- Filter -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="{{ route('pengguna.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="w-full border rounded-lg px-4 py-2" placeholder="Nama atau username...">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
            <select name="role" class="w-full border rounded-lg px-4 py-2">
                <option value="">Semua Role</option>
                <option value="Admin" {{ request('role') === 'Admin' ? 'selected' : '' }}>Admin</option>
                <option value="Petugas" {{ request('role') === 'Petugas' ? 'selected' : '' }}>Petugas</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-search mr-1"></i>Filter
            </button>
            <a href="{{ route('pengguna.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Terdaftar</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($pengguna as $index => $p)
                <tr class="{{ $p->id_pengguna === auth()->id() ? 'bg-blue-50' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        {{ $pengguna->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($p->nama, 0, 1)) }}
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="font-medium text-gray-900">
                                    {{ $p->nama }}
                                    @if($p->id_pengguna === auth()->id())
                                        <span class="ml-2 px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                            <i class="fas fa-user"></i> Anda
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <i class="fas fa-user-circle mr-1"></i>{{ $p->username }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 text-xs rounded-full {{ $p->role === 'Admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                            <i class="fas {{ $p->role === 'Admin' ? 'fa-crown' : 'fa-user-tie' }} mr-1"></i>
                            {{ $p->role }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $p->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <a href="{{ route('pengguna.show', $p->id_pengguna) }}" class="text-blue-600 hover:text-blue-900 mr-3" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('pengguna.edit', $p->id_pengguna) }}" class="text-yellow-600 hover:text-yellow-900 mr-3" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($p->id_pengguna !== auth()->id())
                            <form action="{{ route('pengguna.destroy', $p->id_pengguna) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data pengguna</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $pengguna->links() }}
</div>
@endsection