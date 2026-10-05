@extends('layouts.app')
@section('title', 'Daftar Siswa PKL')

@php
    $now = \Carbon\Carbon::now('Asia/Jakarta');
@endphp

@section('content')
<div class="max-w-4xl mx-auto pb-24 md:pb-12" x-data="karyawanSiswaApp()">

    {{-- ── Hero Header ─────────────────────────────────────────────── --}}
    <section class="relative bg-primary overflow-hidden shadow-sm" style="border-radius: 0 0 32px 32px;">
        {{-- Ornamen lingkaran dekoratif --}}
        <div class="pointer-events-none absolute -top-12 -right-12 w-48 h-48 rounded-full"
             style="background: rgba(255,255,255,0.08)"></div>
        <div class="pointer-events-none absolute top-8 -right-4 w-28 h-28 rounded-full"
             style="background: rgba(255,255,255,0.06)"></div>
        <div class="pointer-events-none absolute bottom-0 left-0 w-24 h-24 rounded-full"
             style="background: rgba(255,255,255,0.04)"></div>

        <div class="relative z-10 px-md md:px-lg pt-lg pb-lg">
            <div class="flex items-start justify-between">
                {{-- Kiri: Title & Subtitle --}}
                <div class="flex-1 min-w-0 pr-md">
                    <p class="text-white/70 text-[13px] font-medium tracking-wide mb-1 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                        Pemantauan Siswa
                    </p>
                    <h2 class="text-white font-extrabold truncate"
                        style="font-size: 26px; line-height: 32px; letter-spacing: -0.02em;">
                        Daftar Siswa PKL
                    </h2>
                    <p class="text-white/60 text-[12px] leading-[18px] mt-1.5">
                        Status kehadiran dan data siswa magang aktif hari ini
                    </p>
                </div>

                {{-- Kanan: Kalender tanggal --}}
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
        </div>
    </section>

    {{-- ── Main Content ─────────────────────────────────────────────── --}}
    <div class="px-md md:px-lg pt-lg space-y-lg">

        {{-- Pencarian & Filter Tabs --}}
        <section class="space-y-sm">
            <div class="flex items-center justify-between">
                <h3 class="text-[11px] font-bold uppercase tracking-widest text-on-surface-variant/70">
                    Cari & Filter Siswa
                </h3>
                <span class="text-[11px] text-on-surface-variant/60 font-semibold">
                    {{ $todayStats['total'] }} Siswa Aktif
                </span>
            </div>

            {{-- Input Pencarian --}}
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-[18px]">search</span>
                <input type="text"
                       placeholder="Cari nama, jurusan, atau asal sekolah..."
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
        </section>

        {{-- Daftar Siswa --}}
        <section class="space-y-sm">
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

                <div x-show="matchesFilter('{{ strtolower($siswa->name) }}', '{{ strtolower($siswa->jurusan ?? '') }}', '{{ strtolower($siswa->asal_sekolah ?? '') }}', '{{ $statusKey }}')"
                     class="bg-white border border-outline-variant/60 rounded-2xl p-md shadow-sm transition-all hover:border-primary/40">
                    <div class="flex items-center justify-between gap-md">

                        {{-- Avatar & Info Siswa --}}
                        <div class="flex items-center gap-md min-w-0">
                            <div class="w-12 h-12 rounded-full overflow-hidden bg-surface-container flex-shrink-0 border border-outline-variant/40">
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
        </section>

    </div>
</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="siswa" role="karyawan" />
@endsection

@push('scripts')
<script>
function karyawanSiswaApp() {
    return {
        search: '',
        filter: 'semua',

        matchesFilter(name, jurusan, sekolah, status) {
            const query = this.search.trim().toLowerCase();
            const matchesSearch = !query ||
                name.includes(query) ||
                jurusan.includes(query) ||
                sekolah.includes(query);

            const matchesStatus = this.filter === 'semua' || this.filter === status;
            return matchesSearch && matchesStatus;
        }
    };
}
</script>
@endpush
