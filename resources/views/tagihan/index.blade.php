@extends('layouts.app')

@section('title', 'Data Tagihan')

@section('content')
<div class="mb-3">
    <h1 class="text-2xl font-bold text-gray-800">Data Tagihan</h1>
    <p class="text-sm text-gray-600">Kelola Tagihan pelanggan</p>
</div>

<!-- Action Buttons -->
<div class="flex flex-wrap justify-end items-center gap-2 mb-4">

    <!-- Dropdown Cetak -->
    <div class="relative">
        <button type="button"
            onclick="document.getElementById('dropdownPeriode').classList.toggle('hidden')"
            class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded transition text-sm whitespace-nowrap flex items-center">
            <i class="fas fa-print mr-2"></i>
            <span class="hidden sm:inline">Cetak Tagihan</span>
            <span class="sm:hidden">Cetak</span>
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <!-- Dropdown -->
        <div id="dropdownPeriode"
             class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow-lg z-50 max-h-64 overflow-y-auto">
            @foreach($periode_list as $p)
                <a href="{{ route('tagihan.cetak.periode', ['periode' => $p]) }}"
                   target="_blank"
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                    {{ $p }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Button Generate -->
    <button type="button"
        onclick="document.getElementById('modalGenerate').classList.remove('hidden')"
        class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded transition text-sm whitespace-nowrap flex items-center">
        <i class="fas fa-plus-circle mr-2"></i>
        <span class="hidden sm:inline">Generate Tagihan</span>
        <span class="sm:hidden">Generate</span>
    </button>

</div>

<!-- Filter -->
<div class="bg-white rounded-lg shadow mb-4">
    <!-- Toggle Filter Button (Mobile) -->
    <button type="button"
            onclick="document.getElementById('filterForm').classList.toggle('hidden')"
            class="lg:hidden w-full px-4 py-3 flex justify-between items-center text-left border-b border-gray-200 hover:bg-gray-50 transition">
        <span class="font-medium text-gray-700">
            <i class="fas fa-filter mr-2"></i>Filter & Pencarian
        </span>
        <i class="fas fa-chevron-down text-gray-400"></i>
    </button>

    <!-- Filter Form -->
    <div id="filterForm" class="hidden lg:block p-4">
        <form method="GET" action="{{ route('tagihan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Pelanggan</label>
                <input
                    type="text"
                    name="pelanggan"
                    autocomplete="off"
                    {{-- value="{{ request('pelanggan') }}" --}}
                    placeholder="Nama pelanggan..."
                    class="w-full bg-white border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-0 focus:ring-gray-300 focus:border-gray-300">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Periode</label>
                <div class="relative">
                    <button type="button"
                        onclick="document.getElementById('periodeDropdown').classList.toggle('hidden')"
                        class="w-full bg-white border border-gray-300 rounded px-3 py-1.5 text-sm
                               flex justify-between items-center hover:bg-gray-50 transition">
                        <span class="truncate">
                            {{ request('periode') ?? 'Semua Periode' }}
                        </span>
                        <i class="fas fa-chevron-down text-xs text-gray-400 ml-2"></i>
                    </button>

                    <div id="periodeDropdown"
                         class="hidden absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-md max-h-56 overflow-y-auto">
                        <a href="{{ route('tagihan.index', request()->except('periode')) }}"
                           class="block px-3 py-2 text-sm hover:bg-gray-100 transition {{ !request('periode') ? 'bg-gray-50 font-medium' : '' }}">
                            Semua Periode
                        </a>

                        @foreach($periode_list as $p)
                            <a href="{{ route('tagihan.index', array_merge(request()->all(), ['periode' => $p])) }}"
                               class="block px-3 py-2 text-sm hover:bg-gray-100 transition {{ request('periode') == $p ? 'bg-gray-50 font-medium' : '' }}">
                                {{ $p }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                <div class="relative">
                    <button type="button"
                        onclick="document.getElementById('statusDropdown').classList.toggle('hidden')"
                        class="w-full bg-white border border-gray-300 rounded px-3 py-1.5 text-sm
                               flex justify-between items-center hover:bg-gray-50 transition">
                        <span class="truncate">
                            {{ request('status') ?? 'Semua Status' }}
                        </span>
                        <i class="fas fa-chevron-down text-xs text-gray-400 ml-2"></i>
                    </button>

                    <div id="statusDropdown"
                         class="hidden absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-md">
                        <a href="{{ route('tagihan.index', request()->except('status')) }}"
                           class="block px-3 py-2 text-sm hover:bg-gray-100 transition {{ !request('status') ? 'bg-gray-50 font-medium' : '' }}">
                            Semua Status
                        </a>

                        <a href="{{ route('tagihan.index', array_merge(request()->all(), ['status' => 'Belum Bayar'])) }}"
                           class="block px-3 py-2 text-sm hover:bg-gray-100 transition {{ request('status') == 'Belum Bayar' ? 'bg-gray-50 font-medium' : '' }}">
                            Belum Bayar
                        </a>

                        <a href="{{ route('tagihan.index', array_merge(request()->all(), ['status' => 'Tunggakan'])) }}"
                           class="block px-3 py-2 text-sm hover:bg-gray-100 transition {{ request('status') == 'Tunggakan' ? 'bg-gray-50 font-medium' : '' }}">
                            Tunggakan
                        </a>

                        <a href="{{ route('tagihan.index', array_merge(request()->all(), ['status' => 'Lunas'])) }}"
                           class="block px-3 py-2 text-sm hover:bg-gray-100 transition {{ request('status') == 'Lunas' ? 'bg-gray-50 font-medium' : '' }}">
                            Lunas
                        </a>
                    </div>
                </div>
            </div>

            <div class="flex items-end gap-2 ">
                <button type="submit" class="flex-1 bg-gray-700 hover:bg-gray-800 text-white px-3 py-1.5 rounded text-sm transition">
                    <i class="fas fa-search mr-1"></i>
                    <span class="hidden sm:inline">Filter</span>
                </button>
                <a href="{{ route('tagihan.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1.5 rounded text-sm transition"
                   title="Reset Filter">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-700 to-gray-800">
                <tr>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">No</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Pelanggan</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Periode</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider hidden md:table-cell">Tagihan</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider hidden md:table-cell">Terbayar</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Sisa</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Status</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider hidden lg:table-cell">Deposit</th>
                    <th class="px-3 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($tagihan as $index => $t)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                            {{ $tagihan->firstItem() + $index }}
                        </td>
                        <td class="px-3 py-3">
                            <div class="font-medium text-sm text-gray-900">{{ $t->pelanggan->nama }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $t->pelanggan->dusun }}</div>
                            <!-- Info Mobile -->
                            <div class="md:hidden mt-2 space-y-1">
                                <p class="text-xs text-gray-600">
                                    <span class="font-medium">Tagihan:</span>
                                    Rp {{ number_format($t->jml_tagihan_pokok, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-600">
                                    <span class="font-medium">Terbayar:</span>
                                    Rp {{ number_format($t->total_sudah_bayar, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-600 lg:hidden">
                                    <span class="font-medium">Deposit:</span>
                                    Rp {{ number_format($t->pelanggan->deposit->saldo_deposit, 0, ',', '.') }}
                                </p>
                            </div>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                            {{ $t->periode }}
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 hidden md:table-cell">
                            Rp {{ number_format($t->jml_tagihan_pokok, 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 hidden md:table-cell">
                            Rp {{ number_format($t->total_sudah_bayar, 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm font-semibold text-gray-900">
                            Rp {{ number_format($t->sisa_tagihan, 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full
                                {{ $t->status === 'Lunas' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $t->status === 'Tunggakan' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $t->status === 'Belum Bayar' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                {{ $t->status }}
                            </span>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900 hidden lg:table-cell">
                            Rp {{ number_format($t->pelanggan->deposit->saldo_deposit, 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('tagihan.show', $t->id_tagihan) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 text-gray-600 hover:text-gray-700 hover:bg-gray-50 rounded transition-colors"
                                   title="Detail">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                @if($t->status !== 'Lunas')
                                    <a href="{{ route('pembayaran.create', $t->id_tagihan) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:text-green-700 hover:bg-green-50 rounded transition-colors"
                                       title="Bayar">
                                        <i class="fas fa-money-bill-wave text-sm"></i>
                                    </a>
                                @endif
                                @if($t->transaksiPembayaran->count() === 0)
                                    <a href="{{ route('tagihan.edit', $t->id_tagihan) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 hover:text-yellow-700 hover:bg-yellow-50 rounded transition-colors"
                                       title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                @endif
                                @if ($t->transaksiPembayaran->count() === 0)
                                    <form method="POST" action="{{ route('tagihan.destroy', $t->id_tagihan) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus tagihan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-700 hover:bg-red-50 rounded transition-colors"
                                                title="Hapus">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </form>

                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-3 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="fas fa-file-invoice text-5xl mb-3 opacity-50"></i>
                                <p class="text-base font-medium text-gray-500">Tidak ada data tagihan</p>
                                <p class="text-sm text-gray-400 mt-1">Data akan muncul setelah generate tagihan</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($tagihan->hasPages())
    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
        {{ $tagihan->links() }}
    </div>
    @endif
</div>

<!-- Modal Generate Tagihan -->
<div id="modalGenerate" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-900">Generate Tagihan Bulk</h3>
                <button type="button"
                        onclick="document.getElementById('modalGenerate').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('tagihan.generate-bulk') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Periode <span class="text-red-500">*</span>
                        </label>
                        <input type="month"
                               name="periode"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                               required>
                        <p class="text-xs text-gray-500 mt-1">Format: Tahun-Bulan (contoh: 2025-01)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Tagihan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                            <input type="number"
                                   name="jml_tagihan_pokok"
                                   class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                                   value="10000"
                                   min="0"
                                   step="1000"
                                   required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Nominal tagihan untuk semua pelanggan aktif</p>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit"
                            class="flex-1 bg-gray-700 hover:bg-gray-800 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-check mr-2"></i>Generate
                    </button>
                    <button type="button"
                            onclick="document.getElementById('modalGenerate').classList.add('hidden')"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2.5 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script untuk close dropdown ketika klik di luar -->
<script>
document.addEventListener('click', function(event) {
    const dropdowns = ['dropdownPeriode', 'periodeDropdown', 'statusDropdown'];
    dropdowns.forEach(id => {
        const dropdown = document.getElementById(id);
        const isClickInside = event.target.closest(`#${id}`) || event.target.closest(`button[onclick*="${id}"]`);
        if (!isClickInside && dropdown && !dropdown.classList.contains('hidden')) {
            dropdown.classList.add('hidden');
        }
    });
});
</script>

@endsection