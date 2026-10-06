@extends('layouts.app')
@section('title', 'Dashboard Karyawan')

@php
    // ── Logika waktu (Asia/Jakarta) konsisten dengan Dashboard Siswa ──
    $now  = \Carbon\Carbon::now('Asia/Jakarta');
    $hour = $now->hour;

    if ($hour >= 5 && $hour < 11) {
        $greeting  = 'Selamat Pagi';
        $greetIcon = '☀️';
    } elseif ($hour >= 11 && $hour < 14) {
        $greeting  = 'Selamat Siang';
        $greetIcon = '🌤️';
    } elseif ($hour >= 14 && $hour < 18) {
        $greeting  = 'Selamat Sore';
        $greetIcon = '🌇';
    } else {
        $greeting  = 'Selamat Malam';
        $greetIcon = '🌙';
    }
@endphp

@section('content')
<div class="max-w-6xl mx-auto p-4 sm:p-6 space-y-6" x-data="karyawanDashboard()">

    {{-- ══════════════════════════════════════════════════════════
         HERO HEADER CARD (seragam dengan Dashboard Guru)
    ══════════════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden shadow-lg"
             style="background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 55%, #3B82F6 100%); border-radius: 24px; padding: 28px 24px;">
        {{-- Decorative circles --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden" style="border-radius: 24px;">
            <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full" style="background:rgba(255,255,255,0.08); filter: blur(20px);"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full" style="background:rgba(255,255,255,0.06); filter: blur(16px);"></div>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">

            {{-- Greeting --}}
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-white/80 text-[12px] font-bold uppercase tracking-wider mb-1">
                        {{ $greetIcon }} {{ $greeting }} · Pembimbing Lapangan
                    </p>
                    <h1 class="text-white font-extrabold text-[22px] sm:text-[26px] leading-tight tracking-tight">
                        Halo, {{ $user->name }}!
                    </h1>
                    <p class="text-white/80 text-[13px] font-medium mt-1">
                        {{ $now->translatedFormat('l, d F Y') }}
                    </p>
                </div>
            </div>

            {{-- Kalender Date Badge Glassmorphism --}}
            <div class="flex-shrink-0 text-center rounded-2xl px-4 py-3 min-w-[84px] shadow-md"
                 style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.38); backdrop-filter: blur(10px);">
                <p class="text-white/90 text-[10px] font-extrabold uppercase tracking-widest leading-none mb-1">
                    {{ $now->translatedFormat('D') }}
                </p>
                <p class="text-white font-black text-[28px] leading-none tracking-tight my-0.5">
                    {{ $now->day }}
                </p>
                <p class="text-white/90 text-[11px] font-bold uppercase tracking-wider leading-none">
                    {{ $now->translatedFormat('M Y') }}
                </p>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════
         BODY CONTENT
    ══════════════════════════════════════════════════════════ --}}
    <div class="space-y-6 pt-2">

        {{-- ── 1. RINGKASAN SISWA PKL HARI INI ── --}}
        <section>
            <div class="flex items-center justify-between mb-sm">
                <h3 class="text-[11px] font-bold uppercase tracking-widest text-on-surface-variant/70">
                    Ringkasan Siswa PKL Hari Ini
                </h3>
                <span class="text-[11px] text-on-surface-variant/60 font-medium">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-sm">

                {{-- Total Siswa Aktif --}}
                <div class="bg-white rounded-2xl p-md border border-outline-variant/60 shadow-sm flex flex-col justify-between col-span-2 sm:col-span-1">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">groups</span>
                        </div>
                        <span class="text-[9px] font-bold text-primary bg-primary/10 px-1.5 py-0.5 rounded-md">
                            Aktif
                        </span>
                    </div>
                    <div>
                        <p class="font-black text-on-surface"
                           style="font-size: 26px; line-height: 1; letter-spacing: -0.02em;">
                            {{ $todayStats['total'] }}
                        </p>
                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-on-surface-variant/70 mt-1">
                            Total Siswa
                        </p>
                    </div>
                </div>

                {{-- Sudah Absen --}}
                <div class="bg-white rounded-2xl p-md border border-outline-variant/60 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-emerald-500/10 text-emerald-700">
                            <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">check_circle</span>
                        </div>
                        <span class="text-[9px] font-bold text-emerald-800 bg-emerald-50 px-1.5 py-0.5 rounded-md border border-emerald-200/60">
                            Hadir
                        </span>
                    </div>
                    <div>
                        <p class="font-black text-emerald-700"
                           style="font-size: 26px; line-height: 1; letter-spacing: -0.02em;">
                            {{ $todayStats['sudah_absen'] }}
                        </p>
                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-on-surface-variant/70 mt-1">
                            Sudah Absen
                        </p>
                    </div>
                </div>

                {{-- Belum Absen --}}
                <div class="bg-white rounded-2xl p-md border border-outline-variant/60 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-amber-500/10 text-amber-700">
                            <span class="material-symbols-outlined text-[20px]">hourglass_empty</span>
                        </div>
                        <span class="text-[9px] font-bold text-amber-800 bg-amber-50 px-1.5 py-0.5 rounded-md border border-amber-200/60">
                            Menunggu
                        </span>
                    </div>
                    <div>
                        <p class="font-black text-amber-700"
                           style="font-size: 26px; line-height: 1; letter-spacing: -0.02em;">
                            {{ $todayStats['belum_absen'] }}
                        </p>
                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-on-surface-variant/70 mt-1">
                            Belum Absen
                        </p>
                    </div>
                </div>

                {{-- Izin --}}
                <div class="bg-white rounded-2xl p-md border border-outline-variant/60 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-blue-500/10 text-blue-700">
                            <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">event_busy</span>
                        </div>
                        <span class="text-[9px] font-bold text-blue-800 bg-blue-50 px-1.5 py-0.5 rounded-md border border-blue-200/60">
                            Disetujui
                        </span>
                    </div>
                    <div>
                        <p class="font-black text-blue-700"
                           style="font-size: 26px; line-height: 1; letter-spacing: -0.02em;">
                            {{ $todayStats['izin'] }}
                        </p>
                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-on-surface-variant/70 mt-1">
                            Izin
                        </p>
                    </div>
                </div>

                {{-- Alpa --}}
                <div class="bg-white rounded-2xl p-md border border-outline-variant/60 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-red-500/10 text-red-700">
                            <span class="material-symbols-outlined text-[20px]">cancel</span>
                        </div>
                        <span class="text-[9px] font-bold text-red-800 bg-red-50 px-1.5 py-0.5 rounded-md border border-red-200/60">
                            Alpa
                        </span>
                    </div>
                    <div>
                        <p class="font-black text-red-700"
                           style="font-size: 26px; line-height: 1; letter-spacing: -0.02em;">
                            {{ $todayStats['alpa'] }}
                        </p>
                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-on-surface-variant/70 mt-1">
                            Alpa
                        </p>
                    </div>
                </div>

            </div>
        </section>

        {{-- ── 2. DAFTAR SISWA PKL HARI INI ── --}}
        <section class="space-y-sm">
            <div class="flex items-center justify-between">
                <h3 class="text-[11px] font-bold uppercase tracking-widest text-on-surface-variant/70">
                    Daftar Siswa PKL Hari Ini
                </h3>
                <span class="text-[11px] text-on-surface-variant/60 font-semibold">
                    {{ $todaySiswa->count() }} Siswa Terdaftar
                </span>
            </div>

            {{-- Pencarian & Filter Tabs --}}
            <div class="space-y-sm">
                {{-- Input Pencarian --}}
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-[18px]">search</span>
                    <input type="text"
                           placeholder="Cari nama atau jurusan siswa..."
                           x-model="search"
                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-outline-variant/60 rounded-xl text-[13px] text-on-surface placeholder:text-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary shadow-sm transition-all">
                </div>

                {{-- Filter Chips --}}
                <div class="flex items-center gap-1.5 overflow-x-auto hide-scrollbar py-1">
                    <button type="button"
                            @click="filter = 'semua'"
                            :class="filter === 'semua' ? 'bg-primary text-white font-bold shadow-sm' : 'bg-white text-on-surface-variant border border-outline-variant/60 font-medium hover:bg-surface-container'"
                            class="px-3 py-1 rounded-full text-[12px] whitespace-nowrap transition-all">
                        Semua ({{ $todayStats['total'] }})
                    </button>
                    <button type="button"
                            @click="filter = 'hadir'"
                            :class="filter === 'hadir' ? 'bg-blue-600 text-white font-bold shadow-sm' : 'bg-white text-on-surface-variant border border-outline-variant/60 font-medium hover:bg-surface-container'"
                            class="px-3 py-1 rounded-full text-[12px] whitespace-nowrap transition-all">
                        Hadir ({{ $todayStats['sudah_absen'] }})
                    </button>
                    <button type="button"
                            @click="filter = 'belum'"
                            :class="filter === 'belum' ? 'bg-amber-700 text-white font-bold shadow-sm' : 'bg-white text-on-surface-variant border border-outline-variant/60 font-medium hover:bg-surface-container'"
                            class="px-3 py-1 rounded-full text-[12px] whitespace-nowrap transition-all">
                        Belum Absen ({{ $todayStats['belum_absen'] }})
                    </button>
                    <button type="button"
                            @click="filter = 'izin'"
                            :class="filter === 'izin' ? 'bg-blue-700 text-white font-bold shadow-sm' : 'bg-white text-on-surface-variant border border-outline-variant/60 font-medium hover:bg-surface-container'"
                            class="px-3 py-1 rounded-full text-[12px] whitespace-nowrap transition-all">
                        Izin ({{ $todayStats['izin'] }})
                    </button>
                    <button type="button"
                            @click="filter = 'alpa'"
                            :class="filter === 'alpa' ? 'bg-red-700 text-white font-bold shadow-sm' : 'bg-white text-on-surface-variant border border-outline-variant/60 font-medium hover:bg-surface-container'"
                            class="px-3 py-1 rounded-full text-[12px] whitespace-nowrap transition-all">
                        Alpa ({{ $todayStats['alpa'] }})
                    </button>
                </div>
            </div>

            {{-- List Card Siswa --}}
            <div class="space-y-sm pt-1">
                @forelse($todaySiswa as $siswa)
                    @php
                        $att = $siswa->attendances->first();

                        if (! $att) {
                            $statusKey   = 'belum';
                            $statusLabel = 'Belum Absen';
                            $badgeClass  = 'bg-surface-container text-on-surface-variant/70 border border-outline-variant/60';
                            $badgeIcon   = 'hourglass_empty';
                        } elseif (in_array($att->status, ['hadir', 'terlambat'])) {
                            $statusKey   = 'hadir';
                            $statusLabel = $att->status === 'terlambat' ? 'Terlambat' : 'Hadir';
                            $badgeClass  = 'bg-emerald-100 text-emerald-800 border border-emerald-200';
                            $badgeIcon   = 'check_circle';
                        } elseif ($att->status === 'izin') {
                            $statusKey   = 'izin';
                            $statusLabel = 'Izin';
                            $badgeClass  = 'bg-blue-100 text-blue-800 border border-blue-200';
                            $badgeIcon   = 'event_busy';
                        } elseif ($att->status === 'alpa') {
                            $statusKey   = 'alpa';
                            $statusLabel = 'Alpa';
                            $badgeClass  = 'bg-red-100 text-red-800 border border-red-200';
                            $badgeIcon   = 'cancel';
                        } else {
                            $statusKey   = 'belum';
                            $statusLabel = 'Belum Absen';
                            $badgeClass  = 'bg-surface-container text-on-surface-variant/70 border border-outline-variant/60';
                            $badgeIcon   = 'hourglass_empty';
                        }

                        $jamMasuk  = $att?->jam_masuk ? \Carbon\Carbon::parse($att->jam_masuk)->format('H:i') : null;
                        $jamPulang = $att?->jam_pulang ? \Carbon\Carbon::parse($att->jam_pulang)->format('H:i') : null;
                    @endphp

                    <div x-show="matchesFilter('{{ strtolower($siswa->name) }}', '{{ strtolower($siswa->jurusan ?? '') }}', '{{ $statusKey }}')"
                         class="bg-white border border-outline-variant/60 rounded-2xl p-md shadow-sm transition-all hover:border-primary/40">
                        <div class="flex items-center justify-between gap-md">

                            {{-- Avatar & Info Siswa --}}
                            <div class="flex items-center gap-md min-w-0">
                                <div class="w-11 h-11 rounded-full overflow-hidden bg-surface-container flex-shrink-0 border border-outline-variant/40">
                                    <img src="{{ $siswa->photo_url }}"
                                         alt="{{ $siswa->name }}"
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[14px] md:text-[15px] font-bold text-on-surface truncate">
                                        {{ $siswa->name }}
                                    </p>
                                    <p class="text-[12px] text-on-surface-variant/80 truncate mt-0.5">
                                        {{ $siswa->jurusan ?: 'Siswa Magang' }}{{ $siswa->asal_sekolah ? ' · ' . $siswa->asal_sekolah : '' }}
                                    </p>
                                    <div class="flex items-center gap-3 mt-1.5 text-[11px] text-on-surface-variant font-medium">
                                        <span class="inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px] text-primary">login</span>
                                            <span>{{ $jamMasuk ?: '--:--' }}</span>
                                        </span>
                                        <span>·</span>
                                        <span class="inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px] text-amber-600">logout</span>
                                            <span>{{ $jamPulang ?: '--:--' }}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Status Badge --}}
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full whitespace-nowrap {{ $badgeClass }}">
                                    <span class="material-symbols-outlined text-[13px]">{{ $badgeIcon }}</span>
                                    <span>{{ $statusLabel }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white border border-outline-variant/60 rounded-2xl p-xl text-center shadow-sm">
                        <div class="w-12 h-12 rounded-full bg-surface-container mx-auto flex items-center justify-center text-outline mb-2">
                            <span class="material-symbols-outlined text-[24px]">person_off</span>
                        </div>
                        <p class="text-[14px] font-bold text-on-surface">Belum ada siswa magang aktif</p>
                        <p class="text-[12px] text-on-surface-variant mt-1">Data siswa PKL akan muncul di sini secara otomatis saat status magang aktif.</p>
                    </div>
                @endforelse
            </div>
        </section>

    </div>
</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="dashboard" role="karyawan" />
@endsection

@push('scripts')
<script>
function karyawanDashboard() {
    return {
        search: '',
        filter: 'semua',

        matchesFilter(name, jurusan, status) {
            const query = this.search.trim().toLowerCase();
            const matchesSearch = !query || name.includes(query) || jurusan.includes(query);
            const matchesStatus = this.filter === 'semua' || this.filter === status;
            return matchesSearch && matchesStatus;
        }
    };
}
</script>
@endpush
