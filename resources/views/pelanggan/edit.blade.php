@extends('layouts.app')

@section('title', 'Edit Pelanggan')

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Edit Pelanggan</h1>
    <nav class="flex text-xs text-gray-600 mt-2" aria-label="breadcrumb">
        <a href="{{ route('dashboard') }}" class="hover:text-gray-800">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('pelanggan.index') }}" class="hover:text-gray-800">Pelanggan</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">Edit</span>
    </nav>
</div>

<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-800 text-white px-4 py-3 rounded-t-lg">
        <h2 class="text-lg font-bold">Form Edit Pelanggan</h2>
        <p class="text-xs text-gray-300 mt-1">Update informasi pelanggan</p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('pelanggan.update', $pelanggan->id_pelanggan) }}" class="p-4">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-800 p-3 rounded-lg">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                    <span class="font-medium text-sm">Terdapat kesalahan pada form:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 ml-6">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Data Pribadi -->
        <div class="mb-6">
            <h3 class="text-base font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-500">
                Data Pribadi
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nama -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="nama"
                           value="{{ old('nama', $pelanggan->nama) }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-transparent @error('nama') border-red-500 @enderror"
                           placeholder="Masukkan nama lengkap"
                           required>
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="hidden" name="status_aktif" id="statusInput" value="{{ old('status_aktif', $pelanggan->status_aktif) }}" required>
                        <button type="button"
                            onclick="document.getElementById('statusDropdown').classList.toggle('hidden')"
                            class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm
                                   flex justify-between items-center hover:bg-gray-50 @error('status_aktif') border-red-500 @enderror">
                            <span id="statusText">{{ old('status_aktif', $pelanggan->status_aktif) }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        <div id="statusDropdown"
                             class="hidden absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-md">
                            <a href="javascript:void(0)"
                               onclick="selectStatus('Aktif')"
                               class="block px-3 py-2 text-sm hover:bg-gray-100">
                                Aktif
                            </a>
                            <a href="javascript:void(0)"
                               onclick="selectStatus('Nonaktif')"
                               class="block px-3 py-2 text-sm hover:bg-gray-100">
                                Nonaktif
                            </a>
                        </div>
                    </div>
                    @error('status_aktif')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Alamat -->
        <div class="mb-6">
            <h3 class="text-base font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-500">
                Alamat Lengkap
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Dusun -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Dusun <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="hidden" name="dusun" id="dusunInput" value="{{ old('dusun', $pelanggan->dusun) }}" required>
                        <button type="button"
                            onclick="document.getElementById('dusunDropdown').classList.toggle('hidden')"
                            class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm
                                   flex justify-between items-center hover:bg-gray-50 @error('dusun') border-red-500 @enderror">
                            <span id="dusunText">{{ old('dusun', $pelanggan->dusun) ?: '-- Pilih Dusun --' }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        <div id="dusunDropdown"
                             class="hidden absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-md">
                            <a href="javascript:void(0)"
                               onclick="selectDusun('Sambo')"
                               class="block px-3 py-2 text-sm hover:bg-gray-100">
                                Sambo
                            </a>
                            <a href="javascript:void(0)"
                               onclick="selectDusun('Bulak')"
                               class="block px-3 py-2 text-sm hover:bg-gray-100">
                                Bulak
                            </a>
                        </div>
                    </div>
                    @error('dusun')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- RT -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        RT <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="hidden" name="rt" id="rtInput" value="{{ old('rt', $pelanggan->rt) }}" required>
                        <button type="button"
                            onclick="document.getElementById('rtDropdown').classList.toggle('hidden')"
                            class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm
                                   flex justify-between items-center hover:bg-gray-50 @error('rt') border-red-500 @enderror">
                            <span id="rtText">{{ old('rt', $pelanggan->rt) ?: '-- Pilih RT --' }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        <div id="rtDropdown"
                             class="hidden absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-md max-h-48 overflow-auto">
                            <a href="javascript:void(0)"
                               onclick="selectRT('001')"
                               class="block px-3 py-2 text-sm hover:bg-gray-100">
                                001
                            </a>
                            <a href="javascript:void(0)"
                               onclick="selectRT('002')"
                               class="block px-3 py-2 text-sm hover:bg-gray-100">
                                002
                            </a>
                            <a href="javascript:void(0)"
                               onclick="selectRT('003')"
                               class="block px-3 py-2 text-sm hover:bg-gray-100">
                                003
                            </a>
                        </div>
                    </div>
                    @error('rt')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- RW -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        RW <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="hidden" name="rw" id="rwInput" value="{{ old('rw', $pelanggan->rw) }}" required>
                        <button type="button"
                            onclick="document.getElementById('rwDropdown').classList.toggle('hidden')"
                            class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm
                                   flex justify-between items-center hover:bg-gray-50 @error('rw') border-red-500 @enderror">
                            <span id="rwText">{{ old('rw', $pelanggan->rw) ?: '-- Pilih RW --' }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        <div id="rwDropdown"
                             class="hidden absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-md max-h-48 overflow-auto">
                            <a href="javascript:void(0)"
                               onclick="selectRW('001')"
                               class="block px-3 py-2 text-sm hover:bg-gray-100">
                                001
                            </a>
                            <a href="javascript:void(0)"
                               onclick="selectRW('002')"
                               class="block px-3 py-2 text-sm hover:bg-gray-100">
                                002
                            </a>
                            <a href="javascript:void(0)"
                               onclick="selectRW('003')"
                               class="block px-3 py-2 text-sm hover:bg-gray-100">
                                003
                            </a>
                            <a href="javascript:void(0)"
                               onclick="selectRW('004')"
                               class="block px-3 py-2 text-sm hover:bg-gray-100">
                                004
                            </a>
                        </div>
                    </div>
                    @error('rw')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Alamat <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="alamat"
                           autocomplete="off"
                           value="{{ old('alamat', $pelanggan->alamat) }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-transparent @error('alamat') border-red-500 @enderror"
                           placeholder="Contoh: Jl. Mawar No. 123"
                           required>
                    @error('alamat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Koordinat GPS -->
        <div class="mb-6">
            <h3 class="text-base font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-500">
                Koordinat GPS
            </h3>
            <div class="mb-3 bg-blue-50 border-l-4 border-blue-500 p-3 rounded">
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 flex-1">
                        <!-- Kolom Kiri -->
                        <div>
                            <p class="text-xs text-blue-800 font-medium mb-1">
                                Cara Menentukan Koordinat:
                            </p>
                            <ul class="text-xs text-blue-700 ml-4 list-disc space-y-1">
                                <li>Klik pada peta untuk menentukan lokasi</li>
                                <li>Atau seret marker (pin) merah ke lokasi yang diinginkan</li>
                                <li>Koordinat akan terisi otomatis di form</li>
                            </ul>
                        </div>

                        <!-- Kolom Kanan -->
                        <div>
                            <p class="text-xs text-blue-800 font-medium mb-1">
                                Keterangan Marker:
                            </p>
                            <ul class="text-xs text-blue-700 space-y-1">
                                <li class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>
                                    Marker <b>Merah</b> → Untuk menentukan lokasi pelanggan
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span>
                                    Marker <b>Hijau</b> → Lokasi Dusun Bulak
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span>
                                    Marker <b>Biru</b> → Lokasi Dusun Sambo
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Latitude -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Latitude <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           step="0.00000001"
                           name="latitude"
                           value="{{ old('latitude', $pelanggan->latitude) }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-transparent @error('latitude') border-red-500 @enderror"
                           placeholder="Contoh: -7.5678901"
                           required>
                    @error('latitude')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Longitude -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Longitude <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           step="0.00000001"
                           name="longitude"
                           value="{{ old('longitude', $pelanggan->longitude) }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-transparent @error('longitude') border-red-500 @enderror"
                           placeholder="Contoh: 110.8234567"
                           required>
                    @error('longitude')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mt-4">
            <div class="flex items-center justify-between mb-2">
                <label class="block text-xs font-medium text-gray-700">
                    Pilih Lokasi di Peta
                </label>

                <button type="button"
                    id="btnResetView"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1.5 rounded text-xs">
                    <i class="fas fa-crosshairs mr-1"></i> Reset View
                </button>
            </div>

            <div id="mapEdit" class="w-full h-[350px] rounded border"></div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex gap-3 pt-6 border-t border-gray-200">
            <button type="submit"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition text-sm">
                <i class="fas fa-save mr-2"></i>Update Pelanggan
            </button>
            <a href="{{ route('pelanggan.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition font-medium text-sm">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // ========== Custom Dropdown Functions ==========
    function selectStatus(value) {
        document.getElementById('statusInput').value = value;
        document.getElementById('statusText').textContent = value;
        document.getElementById('statusDropdown').classList.add('hidden');
    }

    function selectDusun(value) {
        document.getElementById('dusunInput').value = value;
        document.getElementById('dusunText').textContent = value;
        document.getElementById('dusunDropdown').classList.add('hidden');
    }

    function selectRT(value) {
        document.getElementById('rtInput').value = value;
        document.getElementById('rtText').textContent = value;
        document.getElementById('rtDropdown').classList.add('hidden');
    }

    function selectRW(value) {
        document.getElementById('rwInput').value = value;
        document.getElementById('rwText').textContent = value;
        document.getElementById('rwDropdown').classList.add('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdowns = ['statusDropdown', 'dusunDropdown', 'rtDropdown', 'rwDropdown'];
        dropdowns.forEach(id => {
            const dropdown = document.getElementById(id);
            const button = dropdown.previousElementSibling;
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });

    // ========== Leaflet Map Code ==========
    const defaultLat = {{ $pelanggan->latitude }};
    const defaultLng = {{ $pelanggan->longitude }};

    const mapEdit = L.map('mapEdit').setView([defaultLat, defaultLng], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(mapEdit);

    /* =========================
       ICON MARKER
    ========================== */

    const redIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    const greenIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34]
    });

    const blueIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34]
    });

    /* =========================
       MARKER
    ========================== */

    // Marker pelanggan (MERAH - bisa dipindah)
    const marker = L.marker([defaultLat, defaultLng], {
        draggable: true,
        icon: redIcon
    }).addTo(mapEdit)
      .bindPopup('<b>📍 Lokasi Pelanggan</b>')
      .openPopup();

    // Marker Dusun Bulak (HIJAU)
    const markerBulak = L.marker([-7.031304, 112.466114], {
        icon: greenIcon,
        draggable: false
    }).addTo(mapEdit)
      .bindPopup('<b>Dusun Bulak</b><br>Marker lokasi dusun');

    // Marker Dusun Sambo (BIRU)
    const markerSambo = L.marker([-7.023889, 112.478889], {
        icon: blueIcon,
        draggable: false
    }).addTo(mapEdit)
      .bindPopup('<b>Dusun Sambo</b><br>Marker lokasi dusun');

    /* =========================
       FORM INPUT
    ========================== */

    const inputLat = document.querySelector('[name="latitude"]');
    const inputLng = document.querySelector('[name="longitude"]');

    function updateCoordinates(lat, lng) {
        inputLat.value = lat.toFixed(8);
        inputLng.value = lng.toFixed(8);
    }

    // Klik peta
    mapEdit.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateCoordinates(e.latlng.lat, e.latlng.lng);
    });

    // Drag marker pelanggan
    marker.on('dragend', function(e) {
        const pos = e.target.getLatLng();
        updateCoordinates(pos.lat, pos.lng);
    });

    // Input manual latitude
    inputLat.addEventListener('change', () => {
        const lat = parseFloat(inputLat.value);
        const lng = parseFloat(inputLng.value);
        if (!isNaN(lat) && !isNaN(lng)) {
            marker.setLatLng([lat, lng]);
            mapEdit.setView([lat, lng], 16);
        }
    });

    // Input manual longitude
    inputLng.addEventListener('change', () => {
        const lat = parseFloat(inputLat.value);
        const lng = parseFloat(inputLng.value);
        if (!isNaN(lat) && !isNaN(lng)) {
            marker.setLatLng([lat, lng]);
            mapEdit.setView([lat, lng], 16);
        }
    });

    // Tombol Reset View
    document.getElementById('btnResetView').addEventListener('click', () => {
        marker.setLatLng([defaultLat, defaultLng]);
        mapEdit.setView([defaultLat, defaultLng], 15);
        updateCoordinates(defaultLat, defaultLng);
    });

    console.log('Map Edit initialized with colored markers');
</script>
@endpush