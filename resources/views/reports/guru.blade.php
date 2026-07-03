@extends('layouts.app')
@section('title', 'Rekap Absensi')

@section('content')
<div class="px-md max-w-screen-xl mx-auto pt-lg pb-4">

    {{-- Header --}}
    <section class="mb-lg">
        <h2 class="text-headline-lg-mobile font-bold text-on-surface">Rekap Absensi Siswa</h2>
        <p class="text-body-sm text-on-surface-variant">Ringkasan kehadiran seluruh siswa magang</p>
    </section>

    {{-- Month/Year picker --}}
    <form method="GET" action="{{ route('reports.index') }}" class="mb-lg flex gap-sm max-w-xs">
        <div class="flex-1 relative">
            <select name="month"
                    class="w-full h-touch-target px-md rounded-xl border border-outline-variant
                           bg-surface-container-lowest focus:border-primary appearance-none text-body-sm"
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
                           bg-surface-container-lowest focus:border-primary appearance-none text-body-sm"
                    onchange="this.form.submit()">
                @foreach(range(now()->year - 1, now()->year + 1) as $y)
                    <option value="{{ $y }}" @selected($y == $year)>{{ $y }}</option>
                @endforeach
            </select>
            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-outline">expand_more</span>
        </div>
    </form>

    {{-- Total Summary --}}
    <section class="grid grid-cols-4 gap-sm mb-lg">
        @php
            $cards = [
                ['label' => 'Total Hadir',     'value' => $totalStats['hadir'],     'color' => 'text-primary'],
                ['label' => 'Total Terlambat', 'value' => $totalStats['terlambat'], 'color' => 'text-secondary'],
                ['label' => 'Total Izin',      'value' => $totalStats['izin'],      'color' => 'text-outline'],
                ['label' => 'Total Alpa',      'value' => $totalStats['alpa'],      'color' => 'text-error'],
            ];
        @endphp
        @foreach($cards as $card)
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm text-center">
            <p class="text-headline-md font-bold {{ $card['color'] }}">{{ $card['value'] }}</p>
            <p class="text-label-md text-on-surface-variant leading-tight mt-xs">{{ $card['label'] }}</p>
        </div>
        @endforeach
    </section>

    {{-- Tabel rekap per siswa --}}
    <section class="mb-lg">
        <h3 class="text-label-md text-on-surface-variant uppercase tracking-widest mb-md">Per Siswa</h3>

        {{-- Desktop table --}}
        <div class="hidden md:block bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
            <table class="w-full text-body-sm">
                <thead class="bg-surface-container text-label-md text-on-surface-variant uppercase tracking-wider">
                    <tr>
                        <th class="px-md py-sm text-left">Nama Siswa</th>
                        <th class="px-md py-sm text-left">Jurusan</th>
                        <th class="px-md py-sm text-center">Hadir</th>
                        <th class="px-md py-sm text-center">Terlambat</th>
                        <th class="px-md py-sm text-center">Izin</th>
                        <th class="px-md py-sm text-center">Alpa</th>
                        <th class="px-md py-sm text-center">Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($rekapSiswa as $rekap)
                    @php
                        $total  = $rekap['hadir'] + $rekap['izin'] + $rekap['alpa'];
                        $pct    = $total > 0 ? round(($rekap['hadir'] / $total) * 100) : 0;
                    @endphp
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="px-md py-sm">
                            <div class="flex items-center gap-sm">
                                <img src="{{ $rekap['user']->photo_url }}"
                                     class="w-8 h-8 rounded-full object-cover shrink-0"
                                     alt="{{ $rekap['user']->name }}">
                                <span class="font-semibold text-on-surface">{{ $rekap['user']->name }}</span>
                            </div>
                        </td>
                        <td class="px-md py-sm text-on-surface-variant">{{ $rekap['user']->jurusan ?? '—' }}</td>
                        <td class="px-md py-sm text-center font-semibold text-primary">{{ $rekap['hadir'] }}</td>
                        <td class="px-md py-sm text-center text-secondary">{{ $rekap['terlambat'] }}</td>
                        <td class="px-md py-sm text-center text-outline">{{ $rekap['izin'] }}</td>
                        <td class="px-md py-sm text-center text-error">{{ $rekap['alpa'] }}</td>
                        <td class="px-md py-sm">
                            <div class="flex items-center gap-sm">
                                <div class="flex-1 bg-surface-container rounded-full h-2">
                                    <div class="h-2 rounded-full {{ $pct >= 80 ? 'bg-primary' : ($pct >= 60 ? 'bg-secondary' : 'bg-error') }}"
                                         style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-label-md font-semibold w-10 text-right">{{ $pct }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-md py-lg text-center text-on-surface-variant">
                            Belum ada siswa magang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="md:hidden space-y-sm">
            @forelse($rekapSiswa as $rekap)
            @php
                $total = $rekap['hadir'] + $rekap['izin'] + $rekap['alpa'];
                $pct   = $total > 0 ? round(($rekap['hadir'] / $total) * 100) : 0;
            @endphp
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm space-y-sm">
                <div class="flex items-center gap-sm">
                    <img src="{{ $rekap['user']->photo_url }}"
                         class="w-10 h-10 rounded-full object-cover shrink-0"
                         alt="{{ $rekap['user']->name }}">
                    <div>
                        <p class="text-body-lg font-semibold text-on-surface">{{ $rekap['user']->name }}</p>
                        <p class="text-body-sm text-on-surface-variant">{{ $rekap['user']->jurusan ?? '—' }}</p>
                    </div>
                    <div class="ml-auto text-right">
                        <p class="text-headline-md font-bold {{ $pct >= 80 ? 'text-primary' : ($pct >= 60 ? 'text-secondary' : 'text-error') }}">
                            {{ $pct }}%
                        </p>
                        <p class="text-label-md text-outline">kehadiran</p>
                    </div>
                </div>
                <div class="w-full bg-surface-container rounded-full h-2">
                    <div class="h-2 rounded-full transition-all {{ $pct >= 80 ? 'bg-primary' : ($pct >= 60 ? 'bg-secondary' : 'bg-error') }}"
                         style="width: {{ $pct }}%"></div>
                </div>
                <div class="grid grid-cols-4 gap-sm text-center pt-xs">
                    <div>
                        <p class="text-body-lg font-bold text-primary">{{ $rekap['hadir'] }}</p>
                        <p class="text-label-md text-on-surface-variant">Hadir</p>
                    </div>
                    <div>
                        <p class="text-body-lg font-bold text-secondary">{{ $rekap['terlambat'] }}</p>
                        <p class="text-label-md text-on-surface-variant">Lambat</p>
                    </div>
                    <div>
                        <p class="text-body-lg font-bold text-outline">{{ $rekap['izin'] }}</p>
                        <p class="text-label-md text-on-surface-variant">Izin</p>
                    </div>
                    <div>
                        <p class="text-body-lg font-bold text-error">{{ $rekap['alpa'] }}</p>
                        <p class="text-label-md text-on-surface-variant">Alpa</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg text-center text-on-surface-variant text-body-sm">
                Belum ada siswa magang terdaftar.
            </div>
            @endforelse
        </div>
    </section>

    {{-- Izin pending --}}
    @if($pendingIzin->count())
    <section class="mb-lg">
        <div class="flex justify-between items-center mb-md">
            <h3 class="text-label-md text-on-surface-variant uppercase tracking-widest">Izin Menunggu Persetujuan</h3>
            <span class="text-status-badge font-bold px-sm py-1 rounded-full bg-secondary-container text-on-secondary-container">
                {{ $pendingIzin->count() }}
            </span>
        </div>
        <div class="space-y-sm">
            @foreach($pendingIzin as $perm)
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md
                        flex items-center justify-between gap-3">
                <div class="flex items-center gap-sm min-w-0">
                    <img src="{{ $perm->user->photo_url }}"
                         class="w-10 h-10 rounded-full object-cover shrink-0"
                         alt="{{ $perm->user->name }}">
                    <div class="min-w-0">
                        <p class="text-body-sm font-semibold text-on-surface truncate">{{ $perm->user->name }}</p>
                        <p class="text-label-md text-on-surface-variant">
                            {{ $perm->jenis_label }} · {{ $perm->tanggal->translatedFormat('d M Y') }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('permissions.index') }}"
                   class="text-primary text-label-md font-semibold hover:underline whitespace-nowrap">
                    Tinjau
                </a>
            </div>
            @endforeach
        </div>
    </section>
    @endif

</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="reports" role="guru" />
@endsection
