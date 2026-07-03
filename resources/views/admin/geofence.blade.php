@extends('layouts.app')
@section('title', 'Pengaturan Geofence')

@section('sidebar')
<p class="text-label-md text-outline px-sm py-xs uppercase tracking-wider">Main Menu</p>
<nav class="flex flex-col gap-xs mt-xs">
    <a href="{{ route('dashboard') }}"
       class="flex items-center gap-md p-sm rounded-xl text-on-surface-variant hover:bg-surface-container-high transition-all active:scale-95">
        <span class="material-symbols-outlined">dashboard</span>
        <span class="text-body-lg">Dashboard</span>
    </a>

    <p class="text-label-md text-outline px-sm py-xs uppercase tracking-wider mt-md">Data Master</p>
    <a href="#" class="flex items-center gap-md p-sm rounded-xl text-on-surface-variant hover:bg-surface-container-high transition-all active:scale-95">
        <span class="material-symbols-outlined">person</span>
        <span class="text-body-lg">Siswa Magang</span>
    </a>
    <a href="#" class="flex items-center gap-md p-sm rounded-xl text-on-surface-variant hover:bg-surface-container-high transition-all active:scale-95">
        <span class="material-symbols-outlined">school</span>
        <span class="text-body-lg">Guru Pembimbing</span>
    </a>

    <p class="text-label-md text-outline px-sm py-xs uppercase tracking-wider mt-md">System Settings</p>
    <a href="{{ route('admin.geofence') }}"
       class="flex items-center gap-md p-sm rounded-xl bg-primary-container text-on-primary-container font-semibold transition-all active:scale-95">
        <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">location_on</span>
        <span class="text-body-lg">Pengaturan Geofence</span>
    </a>
</nav>
@endsection

@section('content')
<div class="p-md md:p-lg max-w-5xl mx-auto w-full" x-data="geofenceApp()">

    <div class="mb-lg">
        <h2 class="text-headline-lg-mobile md:text-headline-lg font-bold text-on-background">
            Pengaturan Geofence
        </h2>
        <p class="text-body-sm text-on-surface-variant">
            Konfigurasi radius kehadiran otomatis berdasarkan lokasi kantor atau sekolah.
        </p>
    </div>

    @if(session('success'))
        <div class="mb-lg p-md bg-primary-container text-on-primary-container rounded-xl flex items-center gap-sm">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    {{-- Bento Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg items-start">

        {{-- ── Map Preview ─────────────────────────────────────── --}}
        <div class="lg:col-span-2 bg-surface rounded-xl border border-outline-variant overflow-hidden shadow-sm
                    flex flex-col h-[400px] md:h-[500px]">
            <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface">
                <span class="flex items-center gap-sm text-headline-md font-semibold text-primary">
                    <span class="material-symbols-outlined">map</span>Pratinjau Lokasi
                </span>
                <div class="flex items-center gap-xs text-error text-status-badge font-bold">
                    <span class="w-2 h-2 rounded-full bg-error animate-pulse"></span>LIVE PREVIEW
                </div>
            </div>

            {{-- Leaflet map --}}
            <div id="map" class="flex-1 z-0"></div>
        </div>

        {{-- ── Settings Form ────────────────────────────────────── --}}
        <div class="bg-surface rounded-xl border border-outline-variant shadow-sm p-md flex flex-col gap-lg">
            <div class="flex items-center gap-sm text-headline-md font-semibold text-on-surface">
                <span class="material-symbols-outlined">settings_input_component</span>
                Parameter Lokasi
            </div>

            <form method="POST" action="{{ route('admin.geofence.store') }}" class="flex flex-col gap-md">
                @csrf

                {{-- Nama Lokasi --}}
                <div class="flex flex-col gap-xs">
                    <label class="text-label-md text-on-surface-variant px-1">Nama Lokasi</label>
                    <input type="text" name="nama_lokasi"
                           value="{{ old('nama_lokasi', $geofence->nama_lokasi) }}"
                           placeholder="Contoh: Azmedia Office HQ"
                           class="w-full px-md py-sm border border-outline-variant rounded-lg
                                  focus:border-primary focus:ring-1 focus:ring-primary outline-none text-body-lg"
                           required x-model="namaLokasi">
                    @error('nama_lokasi')<p class="text-body-sm text-error">{{ $message }}</p>@enderror
                </div>

                {{-- Latitude --}}
                <div class="flex flex-col gap-xs">
                    <label class="text-label-md text-on-surface-variant px-1">Latitude</label>
                    <input type="text" name="latitude"
                           value="{{ old('latitude', $geofence->latitude) }}"
                           placeholder="Contoh: -6.1234"
                           class="w-full px-md py-sm border border-outline-variant rounded-lg
                                  focus:border-primary focus:ring-1 focus:ring-primary outline-none text-body-lg"
                           required x-model="lat" @input.debounce.500ms="updateMap">
                    @error('latitude')<p class="text-body-sm text-error">{{ $message }}</p>@enderror
                </div>

                {{-- Longitude --}}
                <div class="flex flex-col gap-xs">
                    <label class="text-label-md text-on-surface-variant px-1">Longitude</label>
                    <input type="text" name="longitude"
                           value="{{ old('longitude', $geofence->longitude) }}"
                           placeholder="Contoh: 106.5678"
                           class="w-full px-md py-sm border border-outline-variant rounded-lg
                                  focus:border-primary focus:ring-1 focus:ring-primary outline-none text-body-lg"
                           required x-model="lng" @input.debounce.500ms="updateMap">
                    @error('longitude')<p class="text-body-sm text-error">{{ $message }}</p>@enderror
                </div>

                {{-- Radius --}}
                <div class="flex flex-col gap-xs">
                    <label class="text-label-md text-on-surface-variant px-1">Radius (Meter)</label>
                    <div class="relative">
                        <input type="number" name="radius"
                               value="{{ old('radius', $geofence->radius ?? 100) }}"
                               min="50" max="5000"
                               placeholder="50 - 5000"
                               class="w-full pl-md pr-xl py-sm border border-outline-variant rounded-lg
                                      focus:border-primary focus:ring-1 focus:ring-primary outline-none text-body-lg"
                               required x-model="radius" @input.debounce.500ms="updateMap">
                        <span class="absolute right-md top-1/2 -translate-y-1/2 text-on-surface-variant text-label-md">m</span>
                    </div>
                    @error('radius')<p class="text-body-sm text-error">{{ $message }}</p>@enderror
                </div>

                {{-- Info box --}}
                <div class="bg-surface-container-low p-sm rounded-lg border border-outline-variant">
                    <div class="flex gap-sm">
                        <span class="material-symbols-outlined text-secondary">info</span>
                        <p class="text-body-sm text-on-surface-variant leading-tight">
                            Radius minimal disarankan adalah 50m untuk menghindari interferensi sinyal GPS di dalam gedung.
                        </p>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-primary text-on-primary font-bold py-md rounded-xl
                               hover:bg-primary/90 shadow-md active:scale-95 transition-all
                               flex items-center justify-center gap-md">
                    <span class="material-symbols-outlined">save</span>Simpan Lokasi
                </button>
            </form>

            {{-- Quick action: use current location --}}
            <div class="pt-md border-t border-outline-variant">
                <p class="text-label-md text-outline uppercase mb-sm">Aksi Cepat</p>
                <button @click="useMyLocation"
                        class="w-full flex items-center gap-md p-sm text-on-surface-variant
                               hover:bg-surface-container rounded-lg transition-all active:scale-95">
                    <span class="material-symbols-outlined">my_location</span>
                    <span class="text-body-sm">Gunakan Lokasi Saya Saat Ini</span>
                </button>
            </div>
        </div>

    </div>

    {{-- Mobile warning --}}
    <div class="mt-lg lg:hidden bg-tertiary-fixed text-on-tertiary-fixed p-md rounded-xl flex gap-md items-start">
        <span class="material-symbols-outlined shrink-0">warning</span>
        <div>
            <p class="font-bold text-body-lg">Perhatian</p>
            <p class="text-body-sm opacity-90">
                Perubahan lokasi geofence akan berdampak langsung pada absensi seluruh siswa dan guru secara real-time.
            </p>
        </div>
    </div>

</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="dashboard" role="admin" />
@endsection

@push('scripts')
{{-- Leaflet.js for interactive map --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV/XN/WLcE=" crossorigin=""></script>

<script>
function geofenceApp() {
    return {
        lat:        '{{ old('latitude', $geofence->latitude ?? -6.2088) }}',
        lng:        '{{ old('longitude', $geofence->longitude ?? 106.8456) }}',
        radius:     '{{ old('radius', $geofence->radius ?? 100) }}',
        namaLokasi: '{{ old('nama_lokasi', $geofence->nama_lokasi ?? '') }}',
        map:        null,
        marker:     null,
        circle:     null,

        init() {
            this.$nextTick(() => {
                this.map = L.map('map').setView([parseFloat(this.lat), parseFloat(this.lng)], 16);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19,
                }).addTo(this.map);

                this.drawMarkerAndCircle();

                // Click on map to set new location
                this.map.on('click', (e) => {
                    this.lat = e.latlng.lat.toFixed(7);
                    this.lng = e.latlng.lng.toFixed(7);
                    this.drawMarkerAndCircle();
                });
            });
        },

        drawMarkerAndCircle() {
            const latF = parseFloat(this.lat);
            const lngF = parseFloat(this.lng);
            if (isNaN(latF) || isNaN(lngF)) return;

            if (this.marker) { this.marker.remove(); }
            if (this.circle)  { this.circle.remove(); }

            this.marker = L.marker([latF, lngF]).addTo(this.map)
                .bindPopup(this.namaLokasi || 'Lokasi')
                .openPopup();

            this.circle = L.circle([latF, lngF], {
                color:       '#006c49',
                fillColor:   '#10b981',
                fillOpacity: 0.15,
                weight:      2,
                radius:      parseInt(this.radius) || 100,
            }).addTo(this.map);

            this.map.setView([latF, lngF], 16);
        },

        updateMap() {
            this.drawMarkerAndCircle();
        },

        useMyLocation() {
            if (!navigator.geolocation) return alert('Geolocation tidak didukung browser Anda.');
            navigator.geolocation.getCurrentPosition((pos) => {
                this.lat = pos.coords.latitude.toFixed(7);
                this.lng = pos.coords.longitude.toFixed(7);
                this.drawMarkerAndCircle();
            });
        },
    };
}
</script>
@endpush
