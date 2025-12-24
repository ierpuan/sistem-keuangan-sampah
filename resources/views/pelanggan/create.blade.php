@extends('layouts.app')

@section('title', 'Tambah Pelanggan')

@push('styles')
<style>
    #mapCreate {
        height: 450px;
        width: 100%;
        border-radius: 0.5rem;
        border: 2px solid #e5e7eb;
    }
    .leaflet-container {
        z-index: 1;
    }
</style>
@endpush

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Tambah Pelanggan</h1>
    <nav class="flex text-xs text-gray-600 mt-2">
        <a href="{{ route('dashboard') }}" class="hover:text-yellow-600">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('pelanggan.index') }}" class="hover:text-yellow-600">Pelanggan</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">Tambah</span>
    </nav>
</div>

<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-800 text-white px-4 py-3 rounded-t-lg">
        <h2 class="text-lg font-bold">Form Pelanggan Baru</h2>
        <p class="text-xs text-gray-300 mt-1">Lengkapi semua field yang bertanda (*) wajib diisi</p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('pelanggan.store') }}" class="p-4">
        @csrf

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
                           autocomplete="off"
                           value="{{ old('nama') }}"
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
                        <input type="hidden" name="status_aktif" id="statusInput" value="{{ old('status_aktif', 'Aktif') }}" required>
                        <button type="button"
                            onclick="document.getElementById('statusDropdown').classList.toggle('hidden')"
                            class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm
                                   flex justify-between items-center hover:bg-gray-50 @error('status_aktif') border-red-500 @enderror">
                            <span id="statusText">{{ old('status_aktif', 'Aktif') }}</span>
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
                        <input type="hidden" name="dusun" id="dusunInput" value="{{ old('dusun') }}" required>
                        <button type="button"
                            onclick="document.getElementById('dusunDropdown').classList.toggle('hidden')"
                            class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm
                                   flex justify-between items-center hover:bg-gray-50 @error('dusun') border-red-500 @enderror">
                            <span id="dusunText" class="text-gray-400">-- Pilih Dusun --</span>
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
                        <input type="hidden" name="rt" id="rtInput" value="{{ old('rt') }}" required>
                        <button type="button"
                            onclick="document.getElementById('rtDropdown').classList.toggle('hidden')"
                            class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm
                                   flex justify-between items-center hover:bg-gray-50 @error('rt') border-red-500 @enderror">
                            <span id="rtText" class="text-gray-400">-- Pilih RT --</span>
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
                        <input type="hidden" name="rw" id="rwInput" value="{{ old('rw') }}" required>
                        <button type="button"
                            onclick="document.getElementById('rwDropdown').classList.toggle('hidden')"
                            class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm
                                   flex justify-between items-center hover:bg-gray-50 @error('rw') border-red-500 @enderror">
                            <span id="rwText" class="text-gray-400">-- Pilih RW --</span>
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
                           value="{{ old('alamat') }}"
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

            <!-- Info Box -->
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


            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Latitude -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Latitude <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                        step="0.00000001"
                        id="latitude"
                        name="latitude"
                        value="{{ old('latitude') }}"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-transparent @error('latitude') border-red-500 @enderror"
                        placeholder="Contoh: -7.1833"
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
                        id="longitude"
                        name="longitude"
                        value="{{ old('longitude') }}"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-transparent @error('longitude') border-red-500 @enderror"
                        placeholder="Contoh: 112.3833"
                        required>
                    @error('longitude')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Map Container -->
            <div>
                <div class="mt-3 flex items-center justify-between">
                    <label class="block text-xs font-medium text-gray-700">
                        Pilih Lokasi di Peta
                    </label>

                    <button type="button"
                        id="btnResetView"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1.5 rounded text-xs transition">
                        <i class="fas fa-crosshairs mr-1"></i> Reset View Peta
                    </button>
                </div>

                <div id="mapCreate" class="mt-2"></div>
            </div>

        </div>

        <!-- Tombol Aksi -->
        <div class="flex gap-3 pt-6 border-t border-gray-200">
            <button type="submit"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition text-sm">
                <i class="fas fa-save mr-2"></i>Simpan Pelanggan
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
        document.getElementById('statusText').classList.remove('text-gray-400');
        document.getElementById('statusDropdown').classList.add('hidden');
    }

    function selectDusun(value) {
        document.getElementById('dusunInput').value = value;
        document.getElementById('dusunText').textContent = value;
        document.getElementById('dusunText').classList.remove('text-gray-400');
        document.getElementById('dusunDropdown').classList.add('hidden');
    }

    function selectRT(value) {
        document.getElementById('rtInput').value = value;
        document.getElementById('rtText').textContent = value;
        document.getElementById('rtText').classList.remove('text-gray-400');
        document.getElementById('rtDropdown').classList.add('hidden');
    }

    function selectRW(value) {
        document.getElementById('rwInput').value = value;
        document.getElementById('rwText').textContent = value;
        document.getElementById('rwText').classList.remove('text-gray-400');
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

    // Set old values if exist
    @if(old('dusun'))
        selectDusun('{{ old('dusun') }}');
    @endif
    @if(old('rt'))
        selectRT('{{ old('rt') }}');
    @endif
    @if(old('rw'))
        selectRW('{{ old('rw') }}');
    @endif

    // ========== Leaflet Map Code ==========
    // Koordinat untuk setiap Dusun (sebagai referensi)
    const dusunLocations = {
        sambo: {
            lat: -7.023889,
            lng: 112.478889,
            name: 'Dusun Sambo'
        },
        bulak: {
            lat: -7.031304,
            lng: 112.466114,
            name: 'Dusun Bulak'
        }
    };

    // Koordinat default awal (tengah antara 2 dusun)
    const defaultLat = -7.027597;
    const defaultLng = 112.472502;

    // Inisialisasi Map
    const mapCreate = L.map('mapCreate').setView([defaultLat, defaultLng], 14);

    // Tile Layer (Peta OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(mapCreate);

    // Custom Icon untuk Marker Pelanggan (Merah - bisa dipindah)
    const redIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    // Icon untuk marker dusun (Biru)
    const blueIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    // Icon untuk marker dusun (Hijau)
    const greenIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    // Tambahkan marker referensi untuk Dusun Sambo (Biru)
    const markerSambo = L.marker([dusunLocations.sambo.lat, dusunLocations.sambo.lng], {
        icon: blueIcon,
        draggable: false
    }).addTo(mapCreate)
    .bindPopup(`
        <div class="text-sm">
            <p class="font-semibold mb-1 text-blue-700">📍 ${dusunLocations.sambo.name}</p>
            <p class="text-xs text-gray-600">Marker lokasi dusun</p>
        </div>
    `);

    // Tambahkan marker referensi untuk Dusun Bulak (Hijau)
    const markerBulak = L.marker([dusunLocations.bulak.lat, dusunLocations.bulak.lng], {
        icon: greenIcon,
        draggable: false
    }).addTo(mapCreate)
    .bindPopup(`
        <div class="text-sm">
            <p class="font-semibold mb-1 text-green-700">📍 ${dusunLocations.bulak.name}</p>
            <p class="text-xs text-gray-600">Marker lokasi dusun</p>
        </div>
    `);

    // Tambahkan Marker Pelanggan (Merah - bisa di-drag)
    let marker = L.marker([defaultLat, defaultLng], {
        draggable: true,
        icon: redIcon
    }).addTo(mapCreate);

    // Set koordinat default ke input jika belum ada nilai
    if (!document.getElementById('latitude').value) {
        document.getElementById('latitude').value = defaultLat.toFixed(8);
    }
    if (!document.getElementById('longitude').value) {
        document.getElementById('longitude').value = defaultLng.toFixed(8);
    }

    // Fungsi untuk update koordinat di form
    function updateCoordinates(lat, lng) {
        document.getElementById('latitude').value = lat.toFixed(8);
        document.getElementById('longitude').value = lng.toFixed(8);
    }

    // Event: Ketika peta diklik
    mapCreate.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        // Pindahkan marker ke posisi klik
        marker.setLatLng([lat, lng]);

        // Update input form
        updateCoordinates(lat, lng);

        // Tampilkan popup
        marker.bindPopup(`
            <div class="text-sm">
                <p class="font-semibold mb-1">✓ Lokasi Dipilih</p>
            </div>
        `).openPopup();
    });

    // Event: Ketika marker di-drag (diseret)
    marker.on('dragend', function(e) {
        const position = marker.getLatLng();
        updateCoordinates(position.lat, position.lng);

        // Tampilkan popup
        marker.bindPopup(`
            <div class="text-sm">
                <p class="font-semibold mb-1">✓ Lokasi Dipilih</p>
                <p class="text-xs text-gray-600">Lat: ${position.lat.toFixed(6)}</p>
                <p class="text-xs text-gray-600">Lng: ${position.lng.toFixed(6)}</p>
            </div>
        `).openPopup();
    });

    // Event: Ketika input latitude/longitude diubah manual
    document.getElementById('latitude').addEventListener('change', function() {
        const lat = parseFloat(this.value);
        const lng = parseFloat(document.getElementById('longitude').value);
        if (!isNaN(lat) && !isNaN(lng)) {
            marker.setLatLng([lat, lng]);
            mapCreate.setView([lat, lng], 16);
        }
    });

    document.getElementById('longitude').addEventListener('change', function() {
        const lat = parseFloat(document.getElementById('latitude').value);
        const lng = parseFloat(this.value);
        if (!isNaN(lat) && !isNaN(lng)) {
            marker.setLatLng([lat, lng]);
            mapCreate.setView([lat, lng], 16);
        }
    });

    // Tombol reset view ke koordinat default
    document.getElementById('btnResetView').addEventListener('click', function () {
        // Pindahkan marker ke posisi default
        marker.setLatLng([defaultLat, defaultLng]);

        // Reset view peta
        mapCreate.setView([defaultLat, defaultLng], 14);

        // Update input koordinat
        updateCoordinates(defaultLat, defaultLng);
    });

    console.log('Map initialized with Dusun markers');
</script>
@endpush