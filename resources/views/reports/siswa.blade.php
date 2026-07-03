@extends('layouts.app')
@section('title', 'Laporan Absensi')

@section('content')
<div class="max-w-lg mx-auto">

    {{-- ════════════════════════════════════════════════════════════
         HERO HEADER
    ════════════════════════════════════════════════════════════ --}}
    <section class="relative bg-primary overflow-hidden px-md pt-lg pb-lg"
             style="border-radius: 0 0 32px 32px;">
        {{-- Ornamen --}}
        <div class="pointer-events-none absolute -top-10 -right-10 w-44 h-44 rounded-full"
             style="background: rgba(255,255,255,0.07)"></div>
        <div class="pointer-events-none absolute top-6 -right-2 w-24 h-24 rounded-full"
             style="background: rgba(255,255,255,0.05)"></div>

        <div class="relative z-10">
            <p class="text-white/60 text-[11px] font-semibold uppercase tracking-widest mb-1">
                Rekap Kehadiran
            </p>
            <h2 class="text-white font-extrabold"
                style="font-size: 24px; line-height: 30px; letter-spacing: -0.02em;">
                Laporan Absensi
            </h2>
            <p class="text-white/60 text-[13px] mt-1">
                Rekap kehadiran selama magang
            </p>

            {{-- ── Filter Bulan & Tahun ── --}}
            <form method="GET" action="{{ route('reports.index') }}"
                  class="flex gap-sm mt-md">
                {{-- Bulan --}}
                <div class="flex-1 relative">
                    <select name="month"
                            class="w-full h-10 pl-md pr-8 rounded-xl text-[13px] font-semibold
                                   appearance-none outline-none border-0 cursor-pointer"
                            style="background: rgba(255,255,255,0.18); color: white;"
                            onchange="this.form.submit()">
                        @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}"
                                @selected($m == $month)
                                style="color: #0b1c30; background: white;">
                            {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                        </option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2
                                 pointer-events-none text-white/80 text-[18px]">expand_more</span>
                </div>
                {{-- Tahun --}}
                <div class="w-28 relative">
                    <select name="year"
                            class="w-full h-10 pl-md pr-8 rounded-xl text-[13px] font-semibold
                                   appearance-none outline-none border-0 cursor-pointer"
                            style="background: rgba(255,255,255,0.18); color: white;"
                            onchange="this.form.submit()">
                        @foreach(range(now()->year - 1, now()->year + 1) as $y)
                        <option value="{{ $y }}"
                                @selected($y == $year)
                                style="color: #0b1c30; background: white;">{{ $y }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2
                                 pointer-events-none text-white/80 text-[18px]">expand_more</span>
                </div>
            </form>
        </div>
    </section>

    <div class="px-md pt-lg pb-xl space-y-lg">

        {{-- ════════════════════════════════════════════════════════
             KARTU STATISTIK
        ════════════════════════════════════════════════════════ --}}
        @php
            $totalWorkdays = $workdays->count();
            $pctHadir      = $totalWorkdays > 0
                ? round(($summary['hadir'] / $totalWorkdays) * 100)
                : 0;

            $stats = [
                [
                    'label' => 'Hadir',
                    'value' => $summary['hadir'],
                    'icon'  => 'check_circle',
                    'color' => 'text-primary',
                    'bg'    => 'rgba(0,108,73,0.10)',
                ],
                [
                    'label' => 'Terlambat',
                    'value' => $summary['terlambat'],
                    'icon'  => 'schedule',
                    'color' => 'text-secondary',
                    'bg'    => 'rgba(133,83,0,0.10)',
                ],
                [
                    'label' => 'Izin',
                    'value' => $summary['izin'],
                    'icon'  => 'event_busy',
                    'color' => 'text-outline',
                    'bg'    => 'rgba(108,122,113,0.12)',
                ],
                [
                    'label' => 'Alpa',
                    'value' => $summary['alpa'],
                    'icon'  => 'cancel',
                    'color' => 'text-error',
                    'bg'    => 'rgba(186,26,26,0.10)',
                ],
            ];
        @endphp

        <div class="grid grid-cols-4 gap-sm">
            @foreach($stats as $stat)
            <div class="bg-white rounded-2xl border border-outline-variant/50 shadow-sm p-sm text-center">
                <div class="w-8 h-8 rounded-xl mx-auto mb-1.5 flex items-center justify-center {{ $stat['color'] }}"
                     style="background: {{ $stat['bg'] }}">
                    <span class="material-symbols-outlined text-[16px]"
                          style="font-variation-settings:'FILL' 1">{{ $stat['icon'] }}</span>
                </div>
                <p class="font-black {{ $stat['color'] }}"
                   style="font-size: 20px; line-height: 1; letter-spacing: -0.02em;">
                    {{ $stat['value'] }}
                </p>
                <p class="text-[10px] font-bold text-on-surface-variant mt-1 leading-tight uppercase tracking-wide">
                    {{ $stat['label'] }}
                </p>
            </div>
            @endforeach
        </div>

        {{-- ════════════════════════════════════════════════════════
             TINGKAT KEHADIRAN
        ════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-outline-variant/50 shadow-sm p-md">
            <div class="flex items-center gap-sm mb-md">
                <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-[18px]"
                          style="font-variation-settings:'FILL' 1">bar_chart</span>
                </div>
                <div class="flex-1">
                    <p class="text-[14px] font-bold text-on-surface">Tingkat Kehadiran</p>
                    <p class="text-[12px] text-on-surface-variant">
                        {{ $summary['hadir'] }} dari {{ $totalWorkdays }} hari kerja
                    </p>
                </div>
                <p class="font-black text-primary"
                   style="font-size: 28px; line-height: 1; letter-spacing: -0.03em;">
                    {{ $pctHadir }}<span class="text-[16px]">%</span>
                </p>
            </div>

            {{-- Progress bar --}}
            <div class="w-full bg-surface-container rounded-full h-2.5">
                <div class="h-2.5 rounded-full transition-all duration-700"
                     style="width: {{ $pctHadir }}%;
                            background: {{ $pctHadir >= 80 ? '#006c49' : ($pctHadir >= 60 ? '#855300' : '#ba1a1a') }}">
                </div>
            </div>

            {{-- Label indikator --}}
            <div class="flex items-center justify-between mt-2">
                <span class="text-[11px] text-outline">0%</span>
                <span class="text-[11px] font-semibold
                             {{ $pctHadir >= 80 ? 'text-primary' : ($pctHadir >= 60 ? 'text-secondary' : 'text-error') }}">
                    @if($pctHadir >= 80) Sangat Baik
                    @elseif($pctHadir >= 60) Cukup Baik
                    @else Perlu Ditingkatkan
                    @endif
                </span>
                <span class="text-[11px] text-outline">100%</span>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════
             DETAIL HARIAN
        ════════════════════════════════════════════════════════ --}}
        <div>
            <h3 class="text-[11px] font-bold uppercase tracking-widest text-on-surface-variant/70 mb-md">
                Detail Harian
            </h3>

            <div class="space-y-sm">
                @forelse($workdays as $day)
                @php
                    $key     = $day->format('Y-m-d');
                    $att     = $attendances->get($key);
                    $status  = $att?->status ?? 'alpa';
                    $isToday = $day->isToday();
                    $isFuture = $day->isFuture();

                    [$dotColor, $badgeClass, $badgeText] = match($status) {
                        'hadir'     => ['bg-emerald-500',    'bg-emerald-100 text-emerald-700',  'Hadir'],
                        'terlambat' => ['bg-amber-500',      'bg-amber-100 text-amber-700',      'Terlambat'],
                        'izin'      => ['bg-slate-400',      'bg-slate-100 text-slate-600',      'Izin'],
                        default     => ['bg-red-400',        'bg-red-100 text-red-600',          'Alpa'],
                    };

                    $jamMasuk  = $att?->jam_masuk  ? \Carbon\Carbon::parse($att->jam_masuk)->format('H:i')  : null;
                    $jamPulang = $att?->jam_pulang ? \Carbon\Carbon::parse($att->jam_pulang)->format('H:i') : null;
                @endphp

                <div class="bg-white rounded-2xl border shadow-sm overflow-hidden
                            {{ $isToday ? 'border-primary' : 'border-outline-variant/50' }}">
                    <div class="flex items-center gap-md px-md py-sm">

                        {{-- Tanggal box --}}
                        <div class="w-12 h-12 rounded-2xl flex flex-col items-center justify-center flex-shrink-0
                                    {{ $isToday ? 'bg-primary text-white' : 'bg-surface-container text-on-surface-variant' }}">
                            <span class="text-[9px] font-extrabold uppercase tracking-widest leading-none">
                                {{ $day->translatedFormat('D') }}
                            </span>
                            <span class="font-black leading-tight" style="font-size: 20px; line-height: 1.1;">
                                {{ $day->format('d') }}
                            </span>
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5">
                                <p class="text-[13px] font-bold text-on-surface">
                                    {{ $day->translatedFormat('d F Y') }}
                                </p>
                                @if($isToday)
                                <span class="text-[10px] font-bold text-primary bg-primary/10
                                             px-1.5 py-0.5 rounded-full">Hari ini</span>
                                @endif
                            </div>

                            @if($isFuture)
                                <p class="text-[12px] text-on-surface-variant/60 mt-0.5">Belum terjadi</p>
                            @elseif($att)
                                <div class="flex items-center gap-sm mt-0.5">
                                    <span class="flex items-center gap-0.5 text-[12px] text-on-surface-variant">
                                        <span class="material-symbols-outlined text-[12px] text-primary">login</span>
                                        {{ $jamMasuk ?? '--:--' }}
                                    </span>
                                    <span class="text-outline/50 text-[10px]">→</span>
                                    <span class="flex items-center gap-0.5 text-[12px] text-on-surface-variant">
                                        <span class="material-symbols-outlined text-[12px] text-outline">logout</span>
                                        {{ $jamPulang ?? '--:--' }}
                                    </span>
                                </div>
                            @else
                                <p class="text-[12px] text-on-surface-variant/60 mt-0.5">Tidak ada catatan</p>
                            @endif
                        </div>

                        {{-- Status badge --}}
                        @if(!$isFuture)
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold
                                     px-2.5 py-1 rounded-full flex-shrink-0 {{ $badgeClass }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                            {{ $badgeText }}
                        </span>
                        @endif

                    </div>
                </div>

                @empty
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-surface-container flex items-center justify-center mb-md">
                        <span class="material-symbols-outlined text-[28px] text-outline">calendar_month</span>
                    </div>
                    <p class="text-[14px] font-bold text-on-surface mb-1">Tidak Ada Hari Kerja</p>
                    <p class="text-[12px] text-on-surface-variant">Tidak ada hari kerja di bulan ini.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════
             IZIN BULAN INI
        ════════════════════════════════════════════════════════ --}}
        @if($permissions->count())
        <div>
            <h3 class="text-[11px] font-bold uppercase tracking-widest text-on-surface-variant/70 mb-md">
                Izin Bulan Ini
            </h3>

            <div class="space-y-sm">
                @foreach($permissions as $perm)
                @php
                    $iconBg    = match($perm->jenis) { 'sakit' => 'rgba(133,83,0,0.10)', 'dispensasi' => 'rgba(0,108,73,0.10)', default => 'rgba(164,58,58,0.10)' };
                    $iconColor = match($perm->jenis) { 'sakit' => 'text-secondary', 'dispensasi' => 'text-primary', default => 'text-tertiary' };
                    $icon      = match($perm->jenis) { 'sakit' => 'medical_services', 'dispensasi' => 'event_available', default => 'info' };
                    $badgeBg   = match($perm->status) { 'disetujui' => 'bg-emerald-100 text-emerald-700', 'ditolak' => 'bg-red-100 text-red-600', default => 'bg-amber-100 text-amber-700' };
                    $dotColor  = match($perm->status) { 'disetujui' => 'bg-emerald-500', 'ditolak' => 'bg-red-500', default => 'bg-amber-500' };
                @endphp

                <div class="bg-white rounded-2xl border border-outline-variant/50 shadow-sm px-md py-sm
                            flex items-center gap-md">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $iconColor }}"
                         style="background: {{ $iconBg }}">
                        <span class="material-symbols-outlined text-[18px]"
                              style="font-variation-settings:'FILL' 1">{{ $icon }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-bold text-on-surface">{{ $perm->jenis_label }}</p>
                        <p class="text-[11px] text-on-surface-variant mt-0.5">
                            {{ $perm->tanggal->translatedFormat('d F Y') }}
                        </p>
                        <p class="text-[11px] text-outline mt-0.5 truncate">{{ $perm->alasan }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold
                                 px-2 py-0.5 rounded-full flex-shrink-0 {{ $badgeBg }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                        {{ $perm->status_label }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="reports" role="siswa" />
@endsection
