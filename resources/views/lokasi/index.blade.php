@extends('layouts.app')

@section('title', 'Lokasi Pelanggan')

@push('styles')
<style>
    #map {
        height: 600px;
        width: 100%;
        border-radius: 0.5rem;
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
</style>
@endpush

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">
        <i class="fas fa-map-marked-alt mr-2 text-blue-600"></i>Lokasi Pelanggan
    </h1>
    <p class="text-gray-600">Peta lokasi rumah pelanggan air bersih</p>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="{{ route('lokasi.index') }}" id="filterForm" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
        <!-- Dusun -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-home mr-1 text-gray-500"></i>Dusun
            </label>
            <select name="dusun" id="filterDusun" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">Semua Dusun</option>
                @foreach($dusun_list as $d)
                    <option value="{{ $d }}" {{ request('dusun') === $d ? 'selected' : '' }}>{{ $d }}</option>
                @endforeach
            </select>
        </div>

        <!-- RT -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-map-pin mr-1 text-gray-500"></i>RT
            </label>
            <select name="rt" id="filterRt" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">Semua RT</option>
                @foreach($rt_list as $r)
                    <option value="{{ $r }}" {{ request('rt') === $r ? 'selected' : '' }}>{{ $r }}</option>
                @endforeach
            </select>
        </div>

        <!-- RW -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-map-marker-alt mr-1 text-gray-500"></i>RW
            </label>
            <select name="rw" id="filterRw" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">Semua RW</option>
                @foreach($rw_list as $rw)
                    <option value="{{ $rw }}" {{ request('rw') === $rw ? 'selected' : '' }}>{{ $rw }}</option>
                @endforeach
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
            <a href="{{ route('lokasi.index') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg flex items-center justify-center">
                <i class="fas fa-redo mr-2"></i>Reset
            </a>
        </div>

        <!-- Info Count -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-2">
            <p class="text-sm text-blue-800">
                <i class="fas fa-users mr-1"></i>
                <strong>{{ $pelanggan->count() }}</strong> Pelanggan
            </p>
        </div>
    </form>
</div>

<!-- Map Section -->
<div class="bg-white rounded-lg shadow p-6">
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-800">
            <i class="fas fa-map mr-2 text-green-600"></i>Peta Lokasi
        </h2>
        {{-- <h3 class="text-sm text-blue-600">
            <i class="fas fa-map-marker-alt mr-1"> lokasi pelanggan aktif</i>
            <i class="fas fa-info-circle mr-1"></i>Klik marker untuk melihat detail pelanggan
        </h3> --}}
        <div class="flex gap-2">
            <button onclick="map.setView([-7.123, 112.345], 13)" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm">
                <i class="fas fa-crosshairs mr-1"></i>Reset View
            </button>
            <button onclick="toggleFullscreen()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm">
                <i class="fas fa-expand mr-1"></i>Fullscreen
            </button>
        </div>
    </div>

    <div id="map"></div>

    @if($pelanggan->count() === 0)
        <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
            <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl mb-2"></i>
            <p class="text-yellow-800">Tidak ada pelanggan dengan koordinat lokasi yang valid.</p>
            <p class="text-sm text-yellow-600 mt-2">Silakan tambahkan koordinat latitude & longitude pada data pelanggan.</p>
        </div>
    @endif
</div>

<!-- Legend -->
<div class="bg-white rounded-lg shadow p-6 mt-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-info-circle mr-2 text-blue-600"></i>Keterangan
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="flex items-center">
            <div class="w-6 h-6 bg-blue-500 rounded-full mr-3"></div>
            <span class="text-sm text-gray-700">Lokasi Pelanggan Aktif</span>
        </div>
        {{-- <div class="flex items-center">
            <div class="w-6 h-6 bg-red-500 rounded-full mr-3"></div>
            <span class="text-sm text-gray-700">Lokasi Pelanggan Nonaktif</span>
        </div> --}}
        <div class="flex items-center">
            <i class="fas fa-mouse-pointer text-gray-500 mr-3 text-xl"></i>
            <span class="text-sm text-gray-700">Klik marker untuk detail</span>
        </div>
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

    const redIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

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
            // document.getElementById('filterForm').submit();
        });
    });

    console.log('Map initialized with', pelangganData.length, 'markers');
</script>
@endpush