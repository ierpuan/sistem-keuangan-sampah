@extends('layouts.app')

@section('title', 'Lokasi Pelanggan')

@push('styles')
<style>
    #map {
        height: 600px;
        width: 100%;
        border-radius: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .leaflet-popup-content {
        margin: 10px;
        min-width: 200px;
    }

    .marker-cluster-small {
        background-color: rgba(59, 130, 246, 0.6);
    }

    .marker-cluster-small div {
        background-color: rgba(59, 130, 246, 0.8);
    }

    /* Fix z-index untuk leaflet container */
    .leaflet-container {
        z-index: 1;
    }

    /* Pastikan pane leaflet tidak terlalu tinggi */
    .leaflet-pane {
        z-index: auto;
    }

    /* Fix untuk leaflet controls */
    .leaflet-control-container {
        position: relative;
        z-index: 10;
    }

    /* Fix untuk popup */
    .leaflet-popup {
        z-index: 1000;
    }
</style>
@endpush

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Lokasi Pelanggan</h1>
    <nav class="flex text-xs text-gray-600 mt-2" aria-label="breadcrumb">
        <a href="{{ route('dashboard') }}" class="hover:text-gray-800">Dashboard</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">Lokasi</span>
    </nav>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-lg shadow mb-4">
    <!-- Toggle Filter Button (Mobile) -->
    <button type="button"
            onclick="document.getElementById('filterForm').classList.toggle('hidden')"
            class="lg:hidden w-full px-4 py-3 flex justify-between items-center text-left border-b border-gray-200">
        <span class="font-medium text-gray-700 text-sm">
            <i class="fas fa-filter mr-2"></i>Filter Lokasi
        </span>
        <i class="fas fa-chevron-down text-gray-400"></i>
    </button>

    <!-- Filter Form -->
    <div id="filterForm" class="hidden lg:block p-4">
        <form method="GET" action="{{ route('lokasi.index') }}">
            <!-- Filter dalam 1 baris -->
            <div class="grid grid-cols-1 md:grid-cols-6 gap-3 items-end">
                <!-- Pencarian Nama/Alamat dengan Dropdown -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        <i class="fas fa-search mr-1"></i>Cari
                    </label>
                    <div class="relative">
                        <input type="text"
                               name="search"
                               id="searchInput"
                               autocomplete="off"
                               value="{{ request('search') }}"
                               class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-0 focus:border-gray-300"
                               placeholder="Cari nama atau alamat...">
                    </div>
                </div>

                <!-- RT -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        <i class="fas fa-home mr-1"></i>RT
                    </label>
                    <div class="relative">
                        <button type="button"
                            onclick="document.getElementById('rtDropdown').classList.toggle('hidden')"
                            class="w-full bg-white border border-gray-300 rounded px-3 py-1.5 text-sm
                                   flex justify-between items-center hover:bg-gray-50">
                            <span>{{ request('rt') ? 'RT ' . request('rt') : 'Semua' }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        <div id="rtDropdown"
                             class="hidden absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-md max-h-48 overflow-auto">
                            <a href="{{ route('lokasi.index', request()->except('rt')) }}"
                               class="block px-3 py-2 text-sm hover:bg-gray-100">
                                Semua
                            </a>

                            @foreach($rt_list as $r)
                                <a href="{{ route('lokasi.index', array_merge(request()->all(), ['rt' => $r])) }}"
                                   class="block px-3 py-2 text-sm hover:bg-gray-100">
                                    RT {{ $r }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- RW -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        <i class="fas fa-map-pin mr-1"></i>RW
                    </label>
                    <div class="relative">
                        <button type="button"
                            onclick="document.getElementById('rwDropdown').classList.toggle('hidden')"
                            class="w-full bg-white border border-gray-300 rounded px-3 py-1.5 text-sm
                                   flex justify-between items-center hover:bg-gray-50">
                            <span>{{ request('rw') ? 'RW ' . request('rw') : 'Semua' }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        <div id="rwDropdown"
                             class="hidden absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-md max-h-48 overflow-auto">
                            <a href="{{ route('lokasi.index', request()->except('rw')) }}"
                               class="block px-3 py-2 text-sm hover:bg-gray-100">
                                Semua
                            </a>

                            @foreach($rw_list as $rw)
                                <a href="{{ route('lokasi.index', array_merge(request()->all(), ['rw' => $rw])) }}"
                                   class="block px-3 py-2 text-sm hover:bg-gray-100">
                                    RW {{ $rw }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- RW -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        <i class="fas fa-map-marker-alt mr-1"></i>RW
                    </label>
                    <div class="relative">
                        <button type="button"
                            onclick="document.getElementById('dusunDropdown').classList.toggle('hidden')"
                            class="w-full bg-white border border-gray-300 rounded px-3 py-1.5 text-sm
                                   flex justify-between items-center hover:bg-gray-50">
                            <span>{{ request('dusun') ?? 'Semua' }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        <div id="dusunDropdown"
                             class="hidden absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-md max-h-48 overflow-auto">
                            <a href="{{ route('lokasi.index', request()->except('dusun')) }}"
                               class="block px-3 py-2 text-sm hover:bg-gray-100">
                                Semua
                            </a>

                            @foreach($dusun_list as $dusun)
                                <a href="{{ route('lokasi.index', array_merge(request()->all(), ['dusun' => $dusun])) }}"
                                   class="block px-3 py-2 text-sm hover:bg-gray-100">
                                    {{ $dusun }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-2">
                    <button type="submit"
                            class="flex-1 bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded text-sm transition duration-200 flex items-center justify-center">
                        <i class="fas fa-filter mr-1"></i>Filter
                    </button>
                    <a href="{{ route('lokasi.index') }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-2 rounded text-sm transition duration-200 flex items-center justify-center"
                       title="Reset Filter">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>

            <!-- Info Row -->
            <div class="mt-3 flex flex-wrap items-center gap-3">
                <!-- Count Info -->
                <div class="bg-blue-50 border border-blue-200 rounded px-3 py-2">
                    <p class="text-xs text-blue-700">
                        <i class="fas fa-users mr-1"></i>
                        <strong>{{ $pelanggan->count() }}</strong> Pelanggan ditemukan
                    </p>
                </div>

                <!-- Info Filter Aktif -->
                @if(request('search') || request('dusun') || request('rt') || request('rw'))
                <div class="flex-1">
                    <div class="bg-gray-50 border border-gray-200 rounded px-3 py-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs text-gray-600">
                                <i class="fas fa-filter mr-1"></i>Filter aktif:
                            </span>
                            @if(request('search'))
                                <span class="inline-flex items-center bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                                    <i class="fas fa-search mr-1"></i>{{ request('search') }}
                                    <a href="{{ route('lokasi.index', array_diff_key(request()->query(), ['search' => ''])) }}"
                                       class="ml-2 text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            @if(request('dusun'))
                                <span class="inline-flex items-center bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
                                    Dusun: {{ request('dusun') }}
                                    <a href="{{ route('lokasi.index', array_diff_key(request()->query(), ['dusun' => ''])) }}"
                                       class="ml-2 text-green-600 hover:text-green-800">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            @if(request('rt'))
                                <span class="inline-flex items-center bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">
                                    RT: {{ request('rt') }}
                                    <a href="{{ route('lokasi.index', array_diff_key(request()->query(), ['rt' => ''])) }}"
                                       class="ml-2 text-purple-600 hover:text-purple-800">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            @if(request('rw'))
                                <span class="inline-flex items-center bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">
                                    RW: {{ request('rw') }}
                                    <a href="{{ route('lokasi.index', array_diff_key(request()->query(), ['rw' => ''])) }}"
                                       class="ml-2 text-yellow-600 hover:text-yellow-800">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </form>
    </div>
</div>


<!-- Map Section -->
<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-800 text-white px-4 py-4 rounded-t-lg">
        <div class="flex flex-col sm:flex-row items-start justify-between gap-3">
            <div class="flex-1">
                <h2 class="text-xl font-bold mb-1">Peta Lokasi</h2>
                <p class="text-xs text-gray-200">
                    <i class="fas fa-info-circle mr-1"></i>Klik marker untuk melihat rute dan detail pelanggan
                </p>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button onclick="map.setView([centerLat, centerLng], 13)"
                        class="flex-1 sm:flex-initial bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded text-xs transition">
                    <i class="fas fa-crosshairs mr-1"></i>Reset View
                </button>
                <button onclick="toggleFullscreen()"
                        class="flex-1 sm:flex-initial bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded text-xs transition">
                    <i class="fas fa-expand mr-1"></i>Fullscreen
                </button>
            </div>
        </div>
    </div>

    <!-- Map Content -->
    <div class="p-4">
        @if($pelanggan->count() === 0)
            <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5"></i>
                    <div class="flex-1">
                        <p class="font-medium text-sm text-yellow-800">Tidak ada pelanggan dengan koordinat lokasi yang valid</p>
                        <p class="text-xs text-yellow-700 mt-1">Silakan tambahkan koordinat latitude & longitude pada data pelanggan atau sesuaikan filter pencarian.</p>
                    </div>
                </div>
            </div>
        @endif
        <div id="map"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Data pelanggan dari Laravel
    const pelangganData = @json($pelangganJson);

    // Inisialisasi Map
    // Koordinat default: Lamongan, East Java
    const defaultLat = -7.116667;
    const defaultLng = 112.416667;

    // Cari center berdasarkan rata-rata koordinat pelanggan
    let centerLat = defaultLat;
    let centerLng = defaultLng;

    if (pelangganData.length > 0) {
        const avgLat = pelangganData.reduce((sum, p) => sum + p.latitude, 0) / pelangganData.length;
        const avgLng = pelangganData.reduce((sum, p) => sum + p.longitude, 0) / pelangganData.length;
        centerLat = avgLat;
        centerLng = avgLng;
    }

    const map = L.map('map').setView([centerLat, centerLng], 13);

    // Tile Layer (Peta)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
    }).addTo(map);

    // Custom Icon
    const blueIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    // const redIcon = L.icon({
    //     iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
    //     shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
    //     iconSize: [25, 41],
    //     iconAnchor: [12, 41],
    //     popupAnchor: [1, -34],
    //     shadowSize: [41, 41]
    // });

    // Tambahkan Marker untuk setiap pelanggan
    const markers = [];
    pelangganData.forEach(function(pelanggan) {
        const icon = pelanggan.status === 'Aktif' ? blueIcon : redIcon;

        const marker = L.marker([pelanggan.latitude, pelanggan.longitude], { icon: icon })
            .addTo(map)
            .bindPopup(`
                <div class="p-2">
                    <h3 class="font-bold text-lg text-gray-800 mb-2">${pelanggan.nama}</h3>
                    <div class="space-y-1 text-sm">
                        <p class="text-gray-600">
                            <i class="fas fa-map-marker-alt text-gray-500 w-4"></i>
                            ${pelanggan.alamat}
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-home text-gray-500 w-4"></i>
                            Dusun: ${pelanggan.dusun || '-'}
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-location-arrow text-gray-500 w-4"></i>
                            RT ${pelanggan.rt || '-'} / RW ${pelanggan.rw || '-'}
                        </p>
                        <p class="mt-2">
                            <span class="px-2 py-1 text-xs rounded-full ${pelanggan.status === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                ${pelanggan.status}
                            </span>
                        </p>
                        <div class="mt-3 pt-2 border-t space-y-2">
                            <a href="/pelanggan/${pelanggan.id}"
                            class="block text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                <i class="fas fa-eye mr-1"></i>Lihat Detail
                            </a>

                            <a href="https://www.google.com/maps/dir/?api=1&destination=${pelanggan.latitude},${pelanggan.longitude}"
                            target="_blank"
                            class="block text-green-600 hover:text-green-800 text-sm font-semibold">
                                <i class="fas fa-directions mr-1"></i>Petunjuk Arah (Google Maps)
                            </a>
                        </div>

                    </div>
                </div>
            `);

        markers.push(marker);
    });

    // Fit bounds jika ada marker
    if (markers.length > 0) {
        const group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
    }

    // Fullscreen function
    function toggleFullscreen() {
        const mapElement = document.getElementById('map');
        if (!document.fullscreenElement) {
            mapElement.requestFullscreen().catch(err => {
                alert(`Error attempting to enable fullscreen: ${err.message}`);
            });
        } else {
            document.exitFullscreen();
        }
    }

    // Resize map on fullscreen change
    document.addEventListener('fullscreenchange', function() {
        setTimeout(function() {
            map.invalidateSize();
        }, 100);
    });

    // Auto-submit filter ketika dropdown berubah (optional)
    document.querySelectorAll('#filterDusun, #filterRt, #filterRw').forEach(function(select) {
        select.addEventListener('change', function() {
            // Uncomment baris berikut jika ingin auto-submit
            // document.querySelector('#filterForm form').submit();
        });
    });

    console.log('Map initialized with', pelangganData.length, 'markers');
</script>
@endpush