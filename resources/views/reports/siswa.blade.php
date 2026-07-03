@extends('layouts.app')
@section('title', 'Laporan Absensi')

@section('content')
<div class="px-md max-w-lg mx-auto pt-lg pb-4">

    {{-- Header --}}
    <section class="mb-lg">
        <h2 class="text-headline-lg-mobile font-bold text-on-surface">Laporan Absensi</h2>
        <p class="text-body-sm text-on-surface-variant">Rekap kehadiran selama magang</p>
    </section>

    {{-- Month/Year picker --}}
    <form method="GET" action="{{ route('reports.index') }}" class="mb-lg flex gap-sm">
        <div class="flex-1 relative">
            <select name="month"
                    class="w-full h-touch-target px-md rounded-xl border border-outline-variant
                           bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary
                           appearance-none text-body-sm"
                    onchange="this.form.submit()">
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" @selected($m == $month)>
                        {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>
            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-outline">expand_more</span>
        </div>
        <div class="w-28 relative">
            <select name="year"
                    class="w-full h-touch-target px-md rounded-xl border border-outline-variant
                           bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary
                           appearance-none text-body-sm"
                    onchange="this.form.submit()">
                @foreach(range(now()->year - 1, now()->year + 1) as $y)
                    <option value="{{ $y }}" @selected($y == $year)>{{ $y }}</option>
                @endforeach
            </select>
            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-outline">expand_more</span>
        </div>
    </form>

    {{-- Summary cards --}}
    <section class="grid grid-cols-4 gap-sm mb-lg">
        @php
            $cards = [
                ['label' => 'Hadir',     'value' => $summary['hadir'],     'color' => 'text-primary',   'bg' => 'bg-primary-container/20'],
                ['label' => 'Terlambat', 'value' => $summary['terlambat'], 'color' => 'text-secondary', 'bg' => 'bg-secondary-container/30'],
                ['label' => 'Izin',      'value' => $summary['izin'],      'color' => 'text-outline',   'bg' => 'bg-surface-container'],
                ['label' => 'Alpa',      'value' => $summary['alpa'],      'color' => 'text-error',     'bg' => 'bg-error-container/20'],
            ];
        @endphp
        @foreach($cards as $card)
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-sm shadow-sm text-center">
            <p class="text-headline-md font-bold {{ $card['color'] }}">{{ $card['value'] }}</p>
            <p class="text-label-md text-on-surface-variant leading-tight mt-xs">{{ $card['label'] }}</p>
        </div>
        @endforeach
    </section>

    {{-- Progress bar kehadiran --}}
    @php
        $totalWorkdays = $workdays->count();
        $pctHadir = $totalWorkdays > 0 ? round(($summary['hadir'] / $totalWorkdays) * 100) : 0;
    @endphp
    <section class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm mb-lg">
        <div class="flex justify-between items-center mb-sm">
            <p class="text-body-sm font-semibold text-on-surface">Tingkat Kehadiran</p>
            <p class="text-headline-md font-bold text-primary">{{ $pctHadir }}%</p>
        </div>
        <div class="w-full bg-surface-container rounded-full h-3">
            <div class="bg-primary h-3 rounded-full transition-all duration-700"
                 style="width: {{ $pctHadir }}%"></div>
        </div>
        <p class="text-label-md text-outline mt-xs">
            {{ $summary['hadir'] }} dari {{ $totalWorkdays }} hari kerja
        </p>
    </section>

    {{-- Detail per hari --}}
    <section class="mb-lg">
        <h3 class="text-label-md text-on-surface-variant uppercase tracking-widest mb-md">Detail Harian</h3>
        <div class="space-y-sm">
            @forelse($workdays as $day)
            @php
                $key = $day->format('Y-m-d');
                $att = $attendances->get($key);
                $status = $att?->status ?? 'alpa';
                [$badgeText, $badgeClass] = match($status) {
                    'hadir'     => ['Hadir',     'bg-primary-container/20 text-primary'],
                    'terlambat' => ['Terlambat', 'bg-secondary-container text-on-secondary-container'],
                    'izin'      => ['Izin',       'bg-surface-container text-outline'],
                    default     => ['Alpa',       'bg-error-container/20 text-error'],
                };
                $isToday = $day->isToday();
                $isFuture = $day->isFuture();
            @endphp
            <div class="bg-surface-container-lowest border rounded-xl p-md flex items-center justify-between
                        {{ $isToday ? 'border-primary' : 'border-outline-variant' }}">
                <div class="flex items-center gap-md">
                    <div class="w-10 h-10 rounded-xl flex flex-col items-center justify-center shrink-0
                                {{ $isToday ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant' }}">
                        <span class="text-[10px] leading-none font-semibold uppercase">
                            {{ $day->translatedFormat('D') }}
                        </span>
                        <span class="text-body-lg font-bold leading-tight">{{ $day->format('d') }}</span>
                    </div>
                    <div>
                        <p class="text-body-sm font-semibold text-on-surface">
                            {{ $day->translatedFormat('d F Y') }}
                            @if($isToday) <span class="text-primary text-label-md ml-1">• Hari ini</span> @endif
                        </p>
                        @if($att && !$isFuture)
                            <p class="text-label-md text-outline">
                                Masuk: {{ $att->jam_masuk ? \Carbon\Carbon::parse($att->jam_masuk)->format('H:i') : '--:--' }}
                                &nbsp;·&nbsp;
                                Pulang: {{ $att->jam_pulang ? \Carbon\Carbon::parse($att->jam_pulang)->format('H:i') : '--:--' }}
                            </p>
                        @elseif($isFuture)
                            <p class="text-label-md text-outline">Belum terjadi</p>
                        @else
                            <p class="text-label-md text-outline">Tidak ada catatan</p>
                        @endif
                    </div>
                </div>
                @if(!$isFuture)
                    <span class="text-status-badge font-bold px-sm py-1 rounded-full whitespace-nowrap {{ $badgeClass }}">
                        {{ $badgeText }}
                    </span>
                @endif
            </div>
            @empty
            <p class="text-body-sm text-on-surface-variant text-center py-lg">
                Tidak ada hari kerja di bulan ini.
            </p>
            @endforelse
        </div>
    </section>

    {{-- Riwayat Izin bulan ini --}}
    @if($permissions->count())
    <section class="mb-lg">
        <h3 class="text-label-md text-on-surface-variant uppercase tracking-widest mb-md">Izin Bulan Ini</h3>
        <div class="space-y-sm">
            @foreach($permissions as $perm)
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex items-center justify-between">
                <div>
                    <p class="text-body-sm font-semibold text-on-surface">{{ $perm->jenis_label }}</p>
                    <p class="text-label-md text-outline">{{ $perm->tanggal->translatedFormat('d F Y') }}</p>
                    <p class="text-label-md text-on-surface-variant mt-xs line-clamp-1">"{{ $perm->alasan }}"</p>
                </div>
                @php
                    $sc = match($perm->status) {
                        'disetujui' => 'bg-primary-container text-on-primary-container',
                        'ditolak'   => 'bg-error-container text-on-error-container',
                        default     => 'bg-secondary-container text-on-secondary-container',
                    };
                @endphp
                <span class="text-status-badge font-bold px-sm py-1 rounded-full whitespace-nowrap {{ $sc }}">
                    {{ $perm->status_label }}
                </span>
            </div>
            @endforeach
        </div>
    </section>
    @endif

</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="reports" role="siswa" />
@endsection
