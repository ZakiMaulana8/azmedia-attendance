@extends('layouts.app')
@section('title', 'Dashboard')

@php
    // ── Logika waktu (Asia/Jakarta) ──────────────────────────────────
    $now  = \Carbon\Carbon::now('Asia/Jakarta');
    $hour = $now->hour;

    if ($hour >= 5 && $hour < 11) {
        $greeting = 'Selamat Pagi';
        $greetIcon = '☀️';
    } elseif ($hour >= 11 && $hour < 14) {
        $greeting = 'Selamat Siang';
        $greetIcon = '🌤️';
    } elseif ($hour >= 14 && $hour < 18) {
        $greeting = 'Selamat Sore';
        $greetIcon = '🌇';
    } else {
        $greeting = 'Selamat Malam';
        $greetIcon = '🌙';
    }

    // ── Quote motivasi berputar per hari ─────────────────────────────
    $quotes = [
        'Disiplin hari ini adalah investasi masa depanmu.',
        'Konsistensi kecil membangun pencapaian besar.',
        'Hadir tepat waktu adalah bentuk profesionalisme.',
        'Setiap hari adalah kesempatan untuk menjadi lebih baik.',
        'Kerja keras yang tulus tidak pernah sia-sia.',
        'Mulai dengan semangat, selesaikan dengan bangga.',
        'Komitmen membedakan yang biasa dan yang luar biasa.',
    ];
    $quote = $quotes[$now->dayOfWeek % count($quotes)];
@endphp

@section('content')
<div class="max-w-lg mx-auto" x-data="attendanceApp()">

    {{-- ════════════════════════════════════════════════════════════
         HERO HEADER
    ════════════════════════════════════════════════════════════ --}}
    <section class="relative bg-primary overflow-hidden" style="border-radius: 0 0 32px 32px;">

        {{-- Ornamen lingkaran dekoratif --}}
        <div class="pointer-events-none absolute -top-12 -right-12 w-48 h-48 rounded-full"
             style="background: rgba(255,255,255,0.08)"></div>
        <div class="pointer-events-none absolute top-8 -right-4 w-28 h-28 rounded-full"
             style="background: rgba(255,255,255,0.06)"></div>
        <div class="pointer-events-none absolute bottom-0 left-0 w-24 h-24 rounded-full"
             style="background: rgba(255,255,255,0.04)"></div>

        <div class="relative z-10 px-md pt-lg">

            {{-- ── Baris Greeting + Kalender ── --}}
            <div class="flex items-start justify-between mb-lg">

                {{-- Kiri: salam & nama --}}
                <div class="flex-1 min-w-0 pr-md">
                    {{-- Salam waktu (kecil, elegan) --}}
                    <p class="text-white/70 text-[13px] font-medium tracking-wide mb-1">
                        {{ $greetIcon }} {{ $greeting }}
                    </p>
                    {{-- Nama (besar & bold) --}}
                    <h2 class="text-white font-extrabold truncate"
                        style="font-size: 26px; line-height: 32px; letter-spacing: -0.02em;">
                        Halo, {{ $user->name }}!
                    </h2>
                    {{-- Quote motivasi --}}
                    <p class="text-white/55 text-[12px] leading-[18px] mt-2 max-w-[200px]">
                        {{ $quote }}
                    </p>
                </div>

                {{-- Kanan: kalender tanggal --}}
                <div class="flex-shrink-0 text-center rounded-2xl px-3 py-2.5 min-w-[72px]"
                     style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2);">
                    <p class="text-white/70 text-[10px] font-bold uppercase tracking-widest leading-none mb-1">
                        {{ $now->translatedFormat('D') }}
                    </p>
                    <p class="text-white font-black leading-none"
                       style="font-size: 38px; letter-spacing: -0.03em; line-height: 1;">
                        {{ $now->format('d') }}
                    </p>
                    <p class="text-white/80 text-[11px] font-semibold uppercase tracking-wider mt-1 leading-none">
                        {{ $now->translatedFormat('M Y') }}
                    </p>
                </div>
            </div>

            {{-- ── Flash Notification ── --}}
            <div x-show="flashMsg"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 :class="flashSuccess ? 'border-white/30' : 'border-red-300/40'"
                 class="mb-md flex items-center gap-sm rounded-xl px-md py-sm text-white text-[13px] font-semibold border"
                 style="background: rgba(255,255,255,0.15)">
                <span class="material-symbols-outlined text-[17px]"
                      x-text="flashSuccess ? 'check_circle' : 'error'"></span>
                <span x-text="flashMsg"></span>
            </div>

            {{-- ── Kartu Jam Masuk & Pulang ── --}}
            <div class="grid grid-cols-2 gap-sm pb-lg">

                {{-- Jam Masuk --}}
                <div class="bg-white rounded-2xl p-md">
                    <div class="flex items-center gap-xs mb-2">
                        <span class="material-symbols-outlined text-primary text-[15px]">login</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">
                            Jam Masuk
                        </span>
                    </div>
                    <p class="font-black text-on-surface"
                       style="font-size: 22px; line-height: 1; letter-spacing: -0.02em;"
                       :class="jamMasuk ? 'text-on-surface' : 'text-on-surface-variant/50'"
                       x-text="jamMasuk || '--:--'">--:--</p>
                    <div class="mt-2">
                        <span x-show="jamMasuk"
                              class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-full"
                              :class="statusMasuk === 'terlambat'
                                  ? 'bg-amber-100 text-amber-700'
                                  : 'bg-emerald-100 text-emerald-700'"
                              x-text="statusMasuk === 'terlambat' ? 'Terlambat' : 'Tepat Waktu'">
                        </span>
                        <span x-show="!jamMasuk"
                              class="inline-flex text-[11px] font-semibold text-on-surface-variant/60">
                            Belum absen
                        </span>
                    </div>
                </div>

                {{-- Jam Pulang --}}
                <div class="bg-white rounded-2xl p-md">
                    <div class="flex items-center gap-xs mb-2">
                        <span class="material-symbols-outlined text-on-surface-variant text-[15px]">logout</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">
                            Jam Pulang
                        </span>
                    </div>
                    <p class="font-black"
                       style="font-size: 22px; line-height: 1; letter-spacing: -0.02em;"
                       :class="jamPulang ? 'text-on-surface' : 'text-on-surface-variant/50'"
                       x-text="jamPulang || '--:--'">--:--</p>
                    <div class="mt-2">
                        <span x-show="jamPulang"
                              class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-full"
                              :class="keterangan === 'Pulang Cepat' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700'"
                              x-text="keterangan || 'Sudah Pulang'">
                            Sudah Pulang
                        </span>
                        <span x-show="!jamPulang"
                              class="inline-flex text-[11px] font-semibold text-on-surface-variant/60">
                            Belum absen
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════
         TOMBOL ABSEN
    ════════════════════════════════════════════════════════════ --}}
    <section class="flex flex-col items-center py-xl px-md">

        {{-- Lingkaran efek background --}}
        <div class="relative flex items-center justify-center mb-lg">
            {{-- Ring luar --}}
            <div class="absolute w-52 h-52 rounded-full border-2 border-primary/10 animate-ping"
                 style="animation-duration: 3s;"></div>
            <div class="absolute w-44 h-44 rounded-full border border-primary/15"></div>

            {{-- Tombol utama --}}
            <button @click="doAbsen"
                    :disabled="loading || absenDone"
                    class="relative w-36 h-36 rounded-full bg-primary flex flex-col items-center justify-center
                           text-white transition-all duration-200 active:scale-90 disabled:opacity-50
                           focus:outline-none focus:ring-4 focus:ring-primary/30"
                    style="box-shadow: 0 8px 32px rgba(0,108,73,0.45), 0 2px 8px rgba(0,108,73,0.2);">

                {{-- Shimmer overlay --}}
                <div class="absolute inset-0 rounded-full pointer-events-none"
                     style="background: linear-gradient(145deg, rgba(255,255,255,0.18) 0%, transparent 60%)"></div>

                <span class="material-symbols-outlined relative z-10"
                      style="font-size: 56px; font-variation-settings: 'wght' 200, 'FILL' 0;"
                      x-text="loading ? 'hourglass_top' : 'fingerprint'">fingerprint</span>

                <span class="text-[11px] font-extrabold uppercase tracking-widest relative z-10 mt-1"
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
        </div>

        {{-- Indikator Lokasi --}}
        <div class="flex items-center gap-xs rounded-full px-md py-2 border transition-all duration-300"
             :class="locationVerified
                ? 'bg-emerald-50 border-emerald-200'
                : 'bg-amber-50 border-amber-200'">
            <span class="material-symbols-outlined text-[15px]"
                  :class="locationVerified ? 'text-emerald-600' : 'text-amber-500'"
                  style="font-variation-settings:'FILL' 1">location_on</span>
            <p class="text-[12px] font-semibold"
               :class="locationVerified ? 'text-emerald-700' : 'text-amber-600'"
               x-text="locationLabel">Mendapatkan lokasi...</p>
        </div>

        <p class="mt-2 text-[11px] text-on-surface-variant/60 font-medium">
            {{ $user->asal_sekolah ?? 'Azmedia Office' }}
        </p>
    </section>

    {{-- ════════════════════════════════════════════════════════════
         LAYANAN CEPAT
    ════════════════════════════════════════════════════════════ --}}
    <section class="px-md mb-lg">
        <h3 class="text-[11px] font-bold uppercase tracking-widest text-on-surface-variant/70 mb-md">
            Layanan Cepat
        </h3>

        <div class="space-y-sm">

            {{-- Izin --}}
            <a href="{{ route('permissions.create') }}"
               class="flex items-center gap-md bg-white border border-outline-variant/60
                      rounded-2xl px-md py-3.5 shadow-sm
                      active:scale-[0.98] active:bg-surface-container transition-all duration-150 group">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background: rgba(0,108,73,0.10);">
                    <span class="material-symbols-outlined text-primary text-[20px]"
                          style="font-variation-settings:'FILL' 1">event_busy</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[14px] font-bold text-on-surface">Pengajuan Izin</p>
                    <p class="text-[12px] text-on-surface-variant mt-0.5">Ajukan dan kelola izin absensi</p>
                </div>
                <span class="material-symbols-outlined text-[18px] text-outline/60 group-active:translate-x-0.5 transition-transform">
                    chevron_right
                </span>
            </a>

            {{-- Laporan --}}
            <a href="{{ route('reports.index') }}"
               class="flex items-center gap-md bg-white border border-outline-variant/60
                      rounded-2xl px-md py-3.5 shadow-sm
                      active:scale-[0.98] active:bg-surface-container transition-all duration-150 group">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background: rgba(133,83,0,0.10);">
                    <span class="material-symbols-outlined text-secondary text-[20px]"
                          style="font-variation-settings:'FILL' 1">bar_chart</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[14px] font-bold text-on-surface">Laporan Kehadiran</p>
                    <p class="text-[12px] text-on-surface-variant mt-0.5">Riwayat dan rekap absensi</p>
                </div>
                <span class="material-symbols-outlined text-[18px] text-outline/60 group-active:translate-x-0.5 transition-transform">
                    chevron_right
                </span>
            </a>

            {{-- Kegiatan --}}
            <a href="{{ route('activity-reports.index') }}"
               class="flex items-center gap-md bg-white border border-outline-variant/60
                      rounded-2xl px-md py-3.5 shadow-sm
                      active:scale-[0.98] active:bg-surface-container transition-all duration-150 group">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background: rgba(164,58,58,0.10);">
                    <span class="material-symbols-outlined text-tertiary text-[20px]"
                          style="font-variation-settings:'FILL' 1">photo_library</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[14px] font-bold text-on-surface">Laporan Kegiatan</p>
                    <p class="text-[12px] text-on-surface-variant mt-0.5">Upload dan kelola dokumentasi kegiatan</p>
                </div>
                <span class="material-symbols-outlined text-[18px] text-outline/60 group-active:translate-x-0.5 transition-transform">
                    chevron_right
                </span>
            </a>

        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════
         INFO MAGANG
    ════════════════════════════════════════════════════════════ --}}
    @if($user->tanggal_mulai && $user->tanggal_selesai)
    @php $progress = $user->progressMagang(); $sisa = $user->sisaHariMagang(); @endphp
    <section class="px-md mb-xl">
        <div class="bg-white border border-outline-variant/60 rounded-2xl p-md shadow-sm">

            {{-- Header --}}
            <div class="flex items-center gap-sm mb-md">
                <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-[18px]"
                          style="font-variation-settings:'FILL' 1">business_center</span>
                </div>
                <p class="text-[15px] font-bold text-on-surface">Informasi Magang</p>
            </div>

            {{-- Grid detail --}}
            <div class="grid grid-cols-2 gap-sm mb-md">
                <div class="bg-surface-container/50 rounded-xl p-sm">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-1">Asal Sekolah</p>
                    <p class="text-[13px] font-semibold text-on-surface leading-tight">
                        {{ $user->asal_sekolah ?? '—' }}
                    </p>
                </div>
                <div class="bg-surface-container/50 rounded-xl p-sm">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-1">Jurusan</p>
                    <p class="text-[13px] font-semibold text-on-surface leading-tight">
                        {{ $user->jurusan ?? '—' }}
                    </p>
                </div>
                <div class="bg-surface-container/50 rounded-xl p-sm">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-1">Tanggal Mulai</p>
                    <p class="text-[13px] font-semibold text-on-surface">
                        {{ $user->tanggal_mulai->translatedFormat('d M Y') }}
                    </p>
                </div>
                <div class="bg-surface-container/50 rounded-xl p-sm">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-1">Tanggal Selesai</p>
                    <p class="text-[13px] font-semibold text-on-surface">
                        {{ $user->tanggal_selesai->translatedFormat('d M Y') }}
                    </p>
                </div>
            </div>

            {{-- Progress bar --}}
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-[11px] font-semibold text-on-surface-variant">Progress Magang</span>
                    <span class="text-[12px] font-black text-primary">{{ $progress }}%</span>
                </div>
                <div class="w-full bg-surface-container rounded-full h-1.5">
                    <div class="bg-primary h-1.5 rounded-full transition-all duration-700"
                         style="width: {{ $progress }}%"></div>
                </div>
                @if($sisa !== null)
                <p class="text-[11px] text-on-surface-variant/70 mt-1.5">
                    {{ $sisa > 0 ? $sisa . ' hari tersisa' : 'Periode magang telah selesai' }}
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function attendanceApp() {
    return {
        loading:          false,
        locationVerified: false,
        locationLabel:    'Mendapatkan lokasi...',
        flashMsg:         '',
        flashSuccess:     true,
        latitude:         null,
        longitude:        null,

        jamMasuk:    @json($today?->jam_masuk  ? \Carbon\Carbon::parse($today->jam_masuk)->format('H:i')  : null),
        jamPulang:   @json($today?->jam_pulang ? \Carbon\Carbon::parse($today->jam_pulang)->format('H:i') : null),
        statusMasuk: @json($today?->status ?? 'hadir'),
        keterangan:  @json($today?->keterangan),

        absenDone:   {{ ($today?->jam_masuk && $today?->jam_pulang) ? 'true' : 'false' }},
        buttonLabel: '{{ $today?->jam_masuk && !$today?->jam_pulang ? "Absen Pulang" : (!$today?->jam_masuk ? "Absen Masuk" : "Selesai") }}',

        init() {
            if (!navigator.geolocation) return;
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
        },

        async doAbsen() {
            if (!this.locationVerified) {
                this.showFlash('Izinkan akses lokasi untuk absen.', false);
                return;
            }

            const type = this.buttonLabel === 'Absen Pulang' ? 'pulang' : 'masuk';

            if (type === 'masuk') {
                await this.executeAbsen('masuk');
                return;
            }

            // Flow Check-out
            const getJakartaHour = () => {
                return new Date(Date.now() + 7 * 3600000).getUTCHours();
            };

            const currentHour = getJakartaHour();

            // Konfirmasi jika pulang sebelum jam 16:00 WIB
            if (currentHour < 16) {
                const earlyConfirm = await Swal.fire({
                    title: 'Konfirmasi Absen Pulang',
                    text: 'Anda akan melakukan absen pulang sebelum jam pulang (16.00). Apakah Anda yakin ingin pulang lebih awal?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#006c49',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Lanjut Pulang',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                });

                if (!earlyConfirm.isConfirmed) {
                    return;
                }
            }

            // Modal input logbook kegiatan harian (wajib diisi)
            const { value: logbook, isConfirmed } = await Swal.fire({
                title: 'Logbook Kegiatan Hari Ini',
                text: 'Tuliskan ringkasan kegiatan atau tugas yang Anda selesaikan hari ini sebelum check-out.',
                input: 'textarea',
                inputPlaceholder: 'Contoh: Menyelesaikan slicing halaman dashboard dan testing respon API...',
                inputAttributes: {
                    'rows': 4,
                    'maxlength': 1000,
                },
                showCancelButton: true,
                confirmButtonColor: '#006c49',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Simpan & Check-out',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                inputValidator: (value) => {
                    if (!value || !value.trim()) {
                        return 'Logbook kegiatan wajib diisi!';
                    }
                }
            });

            if (!isConfirmed || !logbook || !logbook.trim()) {
                return;
            }

            await this.executeAbsen('pulang', logbook.trim());
        },

        async executeAbsen(type, logbook = null) {
            this.loading = true;
            try {
                const payload = {
                    latitude:  this.latitude,
                    longitude: this.longitude,
                    type,
                };

                if (type === 'pulang') {
                    payload.logbook = logbook;
                }

                const res  = await fetch('{{ route('attendance.store') }}', {
                    method:  'POST',
                    headers: {
                        'Content-Type':  'application/json',
                        'Accept':        'application/json',
                        'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();

                if (res.ok && data.success) {
                    this.showFlash(data.message, true);
                    if (type === 'masuk') {
                        this.jamMasuk    = data.jam_masuk;
                        this.statusMasuk = data.status;
                        this.buttonLabel = 'Absen Pulang';
                    } else {
                        this.jamPulang   = data.jam_pulang;
                        this.keterangan  = data.keterangan;
                        this.buttonLabel = 'Selesai';
                        this.absenDone   = true;
                    }
                } else {
                    let errorMsg = data.message || 'Gagal memproses absensi.';
                    if (data.errors) {
                        const firstError = Object.values(data.errors).flat()[0];
                        if (firstError) errorMsg = firstError;
                    }
                    this.showFlash(errorMsg, false);
                }
            } catch {
                this.showFlash('Terjadi kesalahan jaringan atau server. Coba lagi.', false);
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
