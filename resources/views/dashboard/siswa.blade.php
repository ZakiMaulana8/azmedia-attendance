@extends('layouts.app')
@section('title', 'Dashboard Siswa')

@section('content')
<div class="px-md max-w-lg mx-auto pt-lg" x-data="attendanceApp()">

    {{-- Greeting --}}
    <section class="mb-lg">
        <h2 class="text-headline-lg-mobile font-bold text-on-surface">Halo, {{ $user->name }}</h2>
        <p class="text-body-sm text-on-surface-variant">
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </p>
    </section>

    {{-- Flash message --}}
    <div x-show="flashMsg" x-transition
         :class="flashSuccess ? 'bg-primary-container text-on-primary-container' : 'bg-error-container text-on-error-container'"
         class="mb-md p-sm rounded-xl text-body-sm font-semibold flex items-center gap-sm">
        <span class="material-symbols-outlined text-[18px]" x-text="flashSuccess ? 'check_circle' : 'error'"></span>
        <span x-text="flashMsg"></span>
    </div>

    {{-- Status Cards --}}
    <section class="grid grid-cols-2 gap-md mb-lg">
        {{-- Jam Masuk --}}
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm">
            <div class="flex items-center gap-xs mb-xs text-primary">
                <span class="material-symbols-outlined text-[18px]">login</span>
                <span class="text-label-md uppercase tracking-wider">Jam Masuk</span>
            </div>
            <p class="text-headline-md font-semibold"
               :class="jamMasuk ? 'text-on-surface' : 'text-on-surface-variant'"
               x-text="jamMasuk || '--:--'"></p>
            <span x-show="jamMasuk"
                  class="inline-block mt-2 text-status-badge font-bold px-2 py-0.5 rounded-full"
                  :class="statusMasuk === 'terlambat'
                      ? 'bg-secondary-container text-on-secondary-container'
                      : 'bg-primary-container/20 text-primary'"
                  x-text="statusMasuk === 'terlambat' ? 'Terlambat' : 'Tepat Waktu'">
            </span>
            <span x-show="!jamMasuk"
                  class="inline-block mt-2 text-status-badge font-bold px-2 py-0.5 rounded-full bg-surface-container text-outline">
                Belum Absen
            </span>
        </div>

        {{-- Jam Pulang --}}
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm">
            <div class="flex items-center gap-xs mb-xs text-on-surface-variant">
                <span class="material-symbols-outlined text-[18px]">logout</span>
                <span class="text-label-md uppercase tracking-wider">Jam Pulang</span>
            </div>
            <p class="text-headline-md font-semibold"
               :class="jamPulang ? 'text-on-surface' : 'text-on-surface-variant'"
               x-text="jamPulang || '--:--'"></p>
            <span x-show="jamPulang"
                  class="inline-block mt-2 text-status-badge font-bold px-2 py-0.5 rounded-full bg-primary-container/20 text-primary">
                Sudah Pulang
            </span>
            <span x-show="!jamPulang"
                  class="inline-block mt-2 text-status-badge font-bold px-2 py-0.5 rounded-full bg-surface-container text-outline">
                Belum Absen
            </span>
        </div>
    </section>

    {{-- Main Attendance Button --}}
    <section class="flex flex-col items-center justify-center py-xl relative">
        {{-- Pulse background --}}
        <div class="absolute inset-0 flex items-center justify-center -z-10 overflow-hidden pointer-events-none">
            <div class="w-64 h-64 bg-primary-container/10 rounded-full animate-pulse"></div>
        </div>

        {{-- Button --}}
        <button @click="doAbsen"
                :disabled="loading || absenDone"
                class="w-48 h-48 rounded-full bg-primary-container flex flex-col items-center justify-center
                       text-on-primary-container shadow-xl active:scale-90 transition-transform duration-200
                       border-4 border-white disabled:opacity-60">
            <span class="material-symbols-outlined text-[64px] mb-2"
                  style="font-variation-settings:'wght' 300"
                  x-text="loading ? 'hourglass_top' : 'fingerprint'">fingerprint</span>
            <span class="text-headline-md font-extrabold uppercase tracking-tight"
                  x-text="buttonLabel">
                @if($today?->jam_masuk && !$today?->jam_pulang)
                    Absen Pulang
                @elseif(!$today?->jam_masuk)
                    Absen Masuk
                @else
                    Selesai
                @endif
            </span>
        </button>

        {{-- Location indicator --}}
        <div class="mt-lg flex items-center gap-1 rounded-full px-md py-sm"
             :class="locationVerified ? 'bg-primary-container/10' : 'bg-error-container/20'">
            <span class="material-symbols-outlined text-[18px]"
                  :class="locationVerified ? 'text-primary' : 'text-error'"
                  style="font-variation-settings:'FILL' 1">location_on</span>
            <p class="text-body-sm font-semibold" :class="locationVerified ? 'text-primary' : 'text-error'"
               x-text="locationLabel">Mendapatkan lokasi...</p>
        </div>
        <p class="mt-2 text-label-md text-outline">{{ $user->asal_sekolah ?? 'Azmedia Office' }}</p>
    </section>

    {{-- Quick Access --}}
    <section class="mb-xl">
        <h3 class="text-label-md text-on-surface-variant uppercase tracking-widest mb-md ml-1">
            Layanan Cepat
        </h3>
        <div class="grid grid-cols-3 gap-md">
            <a href="{{ route('permissions.create') }}"
               class="flex flex-col items-center gap-sm group">
                <div class="w-touch-target h-touch-target bg-surface-container rounded-xl flex items-center justify-center
                            text-on-surface group-active:bg-secondary-container transition-colors">
                    <span class="material-symbols-outlined">event_busy</span>
                </div>
                <span class="text-label-md text-center">Ajukan Izin</span>
            </a>
            <a href="{{ route('activity-reports.create') }}"
               class="flex flex-col items-center gap-sm group">
                <div class="w-touch-target h-touch-target bg-surface-container rounded-xl flex items-center justify-center
                            text-on-surface group-active:bg-primary-container transition-colors">
                    <span class="material-symbols-outlined">add_photo_alternate</span>
                </div>
                <span class="text-label-md text-center">Upload Kegiatan</span>
            </a>
            <a href="{{ route('activity-reports.index') }}"
               class="flex flex-col items-center gap-sm group">
                <div class="w-touch-target h-touch-target bg-surface-container rounded-xl flex items-center justify-center
                            text-on-surface group-active:bg-secondary-container transition-colors">
                    <span class="material-symbols-outlined">photo_library</span>
                </div>
                <span class="text-label-md text-center">Laporan Kegiatan</span>
            </a>
        </div>
    </section>

    {{-- Info Magang --}}
    @if($user->tanggal_mulai && $user->tanggal_selesai)
    <section class="mb-xl">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm space-y-md">
            <div class="flex items-center gap-sm">
                <span class="material-symbols-outlined text-primary" style="font-variation-settings:'FILL' 1">
                    business_center
                </span>
                <p class="text-body-lg font-semibold text-on-surface">Info Magang</p>
            </div>

            <div class="grid grid-cols-2 gap-sm text-body-sm">
                <div>
                    <p class="text-label-md text-outline uppercase">Asal Sekolah</p>
                    <p class="text-on-surface font-semibold">{{ $user->asal_sekolah ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-label-md text-outline uppercase">Jurusan</p>
                    <p class="text-on-surface font-semibold">{{ $user->jurusan ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-label-md text-outline uppercase">Mulai</p>
                    <p class="text-on-surface font-semibold">
                        {{ $user->tanggal_mulai->translatedFormat('d M Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-label-md text-outline uppercase">Selesai</p>
                    <p class="text-on-surface font-semibold">
                        {{ $user->tanggal_selesai->translatedFormat('d M Y') }}
                    </p>
                </div>
            </div>

            {{-- Progress bar --}}
            @php $progress = $user->progressMagang(); @endphp
            <div>
                <div class="flex justify-between text-label-md text-on-surface-variant mb-xs">
                    <span>Progress Magang</span>
                    <span class="font-semibold text-primary">{{ $progress }}%</span>
                </div>
                <div class="w-full bg-surface-container rounded-full h-2">
                    <div class="bg-primary h-2 rounded-full transition-all duration-700"
                         style="width: {{ $progress }}%"></div>
                </div>
                @php $sisa = $user->sisaHariMagang(); @endphp
                @if($sisa !== null)
                    <p class="text-label-md text-outline mt-xs">
                        {{ $sisa > 0 ? $sisa . ' hari lagi' : 'Periode magang telah selesai' }}
                    </p>
                @endif
            </div>
        </div>
    </section>
    @endif

</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="dashboard" role="siswa" />
@endsection

@push('scripts')
<script>
function attendanceApp() {
    return {
        loading: false,
        locationVerified: false,
        locationLabel: 'Mendapatkan lokasi...',
        flashMsg: '',
        flashSuccess: true,
        latitude: null,
        longitude: null,

        // State jam — diisi dari server saat load, diupdate reactive setelah absen
        jamMasuk:   @json($today?->jam_masuk ? \Carbon\Carbon::parse($today->jam_masuk)->format('H:i') : null),
        jamPulang:  @json($today?->jam_pulang ? \Carbon\Carbon::parse($today->jam_pulang)->format('H:i') : null),
        statusMasuk: @json($today?->status ?? 'hadir'),

        absenDone:   {{ ($today?->jam_masuk && $today?->jam_pulang) ? 'true' : 'false' }},
        buttonLabel: '{{ $today?->jam_masuk && !$today?->jam_pulang ? "Absen Pulang" : (!$today?->jam_masuk ? "Absen Masuk" : "Selesai") }}',

        init() {
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(
                    (pos) => {
                        this.latitude         = pos.coords.latitude;
                        this.longitude        = pos.coords.longitude;
                        this.locationVerified = true;
                        this.locationLabel    = 'Lokasi terverifikasi ✓';
                    },
                    () => {
                        this.locationVerified = false;
                        this.locationLabel    = 'Lokasi tidak tersedia';
                    },
                    { enableHighAccuracy: true }
                );
            }
        },

        async doAbsen() {
            if (!this.locationVerified) {
                this.showFlash('Izinkan akses lokasi untuk absen.', false);
                return;
            }
            this.loading = true;
            const type   = this.buttonLabel === 'Absen Pulang' ? 'pulang' : 'masuk';

            try {
                const res = await fetch('{{ route('attendance.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        latitude:  this.latitude,
                        longitude: this.longitude,
                        type:      type,
                    }),
                });
                const data = await res.json();

                if (data.success) {
                    this.showFlash(data.message, true);

                    if (type === 'masuk') {
                        // Update kartu jam masuk langsung tanpa reload
                        this.jamMasuk    = data.jam_masuk;
                        this.statusMasuk = data.status;
                        this.buttonLabel = 'Absen Pulang';
                    } else {
                        // Update kartu jam pulang langsung tanpa reload
                        this.jamPulang   = data.jam_pulang;
                        this.buttonLabel = 'Selesai';
                        this.absenDone   = true;
                    }
                } else {
                    this.showFlash(data.message, false);
                }
            } catch {
                this.showFlash('Terjadi kesalahan. Coba lagi.', false);
            } finally {
                this.loading = false;
            }
        },

        showFlash(msg, success) {
            this.flashMsg     = msg;
            this.flashSuccess = success;
            setTimeout(() => this.flashMsg = '', 4000);
        },
    };
}
</script>
@endpush
