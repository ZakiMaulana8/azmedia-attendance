@extends(auth()->user()?->isAdmin() ? 'layouts.admin' : 'layouts.app')
@section('title', 'Rekap Absensi')

@section('content')
<div class="max-w-6xl mx-auto p-4 sm:p-6 space-y-6">

    {{-- ══════════════════════════════════════════════════════════
         HERO HEADER CARD & FILTER
    ══════════════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden shadow-lg"
             style="background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 55%, #3B82F6 100%); border-radius: 24px; padding: 28px 24px;">
        {{-- Decorative circles --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden" style="border-radius: 24px;">
            <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full" style="background:rgba(255,255,255,0.08); filter: blur(20px);"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full" style="background:rgba(255,255,255,0.06); filter: blur(16px);"></div>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">

            {{-- Judul & Subtitle --}}
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-md"
                     style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35); backdrop-filter: blur(8px);">
                    <span class="material-symbols-outlined text-white text-[26px]" style="font-variation-settings:'FILL' 1">bar_chart</span>
                </div>
                <div>
                    <h1 class="text-white font-extrabold text-[22px] sm:text-[24px] leading-tight tracking-tight">
                        Rekap Absensi Siswa
                    </h1>
                    <p class="text-white/80 text-[13px] font-medium mt-0.5">
                        Ringkasan statistik kehadiran dan kedisiplinan siswa magang
                    </p>
                </div>
            </div>

            {{-- Filter Bulan & Tahun Integrated --}}
            <form method="GET" action="{{ route('reports.index') }}" class="flex items-center gap-3 shrink-0">
                <div class="relative min-w-[140px]">
                    <select name="month"
                            class="w-full h-11 pl-4 pr-9 rounded-xl text-white text-[13px] font-bold focus:outline-none appearance-none cursor-pointer shadow-sm transition-all"
                            style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35); backdrop-filter: blur(8px);"
                            onchange="this.form.submit()">
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" @selected($m == $month) class="text-slate-800 font-semibold">
                                {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-white/80 text-[20px]">expand_more</span>
                </div>
                <div class="relative min-w-[100px]">
                    <select name="year"
                            class="w-full h-11 pl-4 pr-9 rounded-xl text-white text-[13px] font-bold focus:outline-none appearance-none cursor-pointer shadow-sm transition-all"
                            style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35); backdrop-filter: blur(8px);"
                            onchange="this.form.submit()">
                        @foreach(range(now()->year - 1, now()->year + 1) as $y)
                            <option value="{{ $y }}" @selected($y == $year) class="text-slate-800 font-semibold">{{ $y }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-white/80 text-[20px]">expand_more</span>
                </div>
            </form>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════
         SUMMARY STATS CARDS (4 GRID SEPARATE CARDS WITH HIGH CONTRAST)
    ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Hadir --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center gap-4 transition-all hover:shadow-md">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                <span class="material-symbols-outlined text-[26px]" style="font-variation-settings:'FILL' 1">check_circle</span>
            </div>
            <div>
                <p class="text-[12px] font-bold uppercase tracking-wider text-slate-500">Hadir</p>
                <h4 class="text-[26px] font-black text-emerald-700 leading-tight">{{ $totalStats['hadir'] }}</h4>
            </div>
        </div>

        {{-- Terlambat --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center gap-4 transition-all hover:shadow-md">
            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                <span class="material-symbols-outlined text-[26px]" style="font-variation-settings:'FILL' 1">schedule</span>
            </div>
            <div>
                <p class="text-[12px] font-bold uppercase tracking-wider text-slate-500">Terlambat</p>
                <h4 class="text-[26px] font-black text-amber-700 leading-tight">{{ $totalStats['terlambat'] }}</h4>
            </div>
        </div>

        {{-- Izin --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center gap-4 transition-all hover:shadow-md">
            <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                <span class="material-symbols-outlined text-[26px]" style="font-variation-settings:'FILL' 1">event_busy</span>
            </div>
            <div>
                <p class="text-[12px] font-bold uppercase tracking-wider text-slate-500">Izin</p>
                <h4 class="text-[26px] font-black text-blue-700 leading-tight">{{ $totalStats['izin'] }}</h4>
            </div>
        </div>

        {{-- Alpa --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center gap-4 transition-all hover:shadow-md">
            <div class="w-12 h-12 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center text-red-600 shrink-0">
                <span class="material-symbols-outlined text-[26px]">cancel</span>
            </div>
            <div>
                <p class="text-[12px] font-bold uppercase tracking-wider text-slate-500">Alpa</p>
                <h4 class="text-[26px] font-black text-red-700 leading-tight">{{ $totalStats['alpa'] }}</h4>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         REKAP PER SISWA TABLE & CARDS
    ══════════════════════════════════════════════════════════ --}}
    <div class="space-y-4 pt-2">

        <div class="flex justify-between items-center">
            <h2 class="text-[18px] font-extrabold text-slate-900 tracking-tight">Rekap Per Siswa</h2>
            <span class="text-[12px] font-bold px-3 py-1 rounded-full bg-blue-50 text-[#2563EB] border border-blue-100">
                📅 {{ \Carbon\Carbon::create(null, $month)->translatedFormat('F') }} {{ $year }}
            </span>
        </div>

        {{-- Desktop table --}}
        <div class="hidden md:block bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80">
                        <th class="px-4 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-widest text-slate-500">Nama Siswa</th>
                        <th class="px-4 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-widest text-slate-500">Jurusan</th>
                        <th class="px-4 py-3.5 text-center text-[11px] font-extrabold uppercase tracking-widest text-blue-600">Hadir</th>
                        <th class="px-4 py-3.5 text-center text-[11px] font-extrabold uppercase tracking-widest text-amber-600">Terlambat</th>
                        <th class="px-4 py-3.5 text-center text-[11px] font-extrabold uppercase tracking-widest text-slate-600">Izin</th>
                        <th class="px-4 py-3.5 text-center text-[11px] font-extrabold uppercase tracking-widest text-red-600">Alpa</th>
                        <th class="px-4 py-3.5 text-center text-[11px] font-extrabold uppercase tracking-widest text-slate-500">Tingkat Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rekapSiswa as $rekap)
                    @php
                        $total    = $rekap['hadir'] + $rekap['izin'] + $rekap['alpa'];
                        $pct      = $total > 0 ? round(($rekap['hadir'] / $total) * 100) : 0;
                        $barColor = $pct >= 80 ? '#2563EB' : ($pct >= 60 ? '#F59E0B' : '#EF4444');
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                <img src="{{ $rekap['user']->photo_url }}"
                                     class="w-8 h-8 rounded-full object-cover ring-2 ring-blue-100 shrink-0"
                                     alt="{{ $rekap['user']->name }}">
                                <span class="font-bold text-slate-900">{{ $rekap['user']->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-slate-600 font-medium">{{ $rekap['user']->jurusan ?? '—' }}</td>
                        <td class="px-4 py-3.5 text-center font-extrabold text-blue-600">{{ $rekap['hadir'] }}</td>
                        <td class="px-4 py-3.5 text-center font-extrabold text-amber-600">{{ $rekap['terlambat'] }}</td>
                        <td class="px-4 py-3.5 text-center font-extrabold text-slate-600">{{ $rekap['izin'] }}</td>
                        <td class="px-4 py-3.5 text-center font-extrabold text-red-600">{{ $rekap['alpa'] }}</td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                    <div class="h-2.5 rounded-full transition-all duration-300"
                                         style="width:{{ $pct }}%;background:{{ $barColor }}"></div>
                                </div>
                                <span class="text-[12px] font-extrabold w-12 text-right"
                                      style="color:{{ $barColor }}">{{ $pct }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-slate-400 font-medium text-[14px]">
                            Belum ada siswa magang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="md:hidden space-y-3">
            @forelse($rekapSiswa as $rekap)
            @php
                $total    = $rekap['hadir'] + $rekap['izin'] + $rekap['alpa'];
                $pct      = $total > 0 ? round(($rekap['hadir'] / $total) * 100) : 0;
                $barColor = $pct >= 80 ? '#2563EB' : ($pct >= 60 ? '#F59E0B' : '#EF4444');
                $pctText  = $pct >= 80 ? '#2563EB' : ($pct >= 60 ? '#F59E0B' : '#EF4444');
            @endphp
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm hover:shadow-md transition-shadow">

                {{-- Header kartu --}}
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ $rekap['user']->photo_url }}"
                         class="w-11 h-11 rounded-full object-cover ring-2 ring-blue-100 shrink-0"
                         alt="{{ $rekap['user']->name }}">
                    <div class="flex-1 min-w-0">
                        <p class="text-[14px] font-bold text-slate-900" style="word-break: break-word; overflow-wrap: break-word; white-space: normal;">{{ $rekap['user']->name }}</p>
                        <p class="text-[12px] text-slate-500 font-medium">{{ $rekap['user']->jurusan ?? '—' }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-[22px] font-black leading-none" style="color:{{ $pctText }}">{{ $pct }}%</p>
                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Kehadiran</p>
                    </div>
                </div>

                {{-- Progress bar --}}
                <div class="w-full bg-slate-100 rounded-full h-2.5 mb-3 overflow-hidden">
                    <div class="h-2.5 rounded-full transition-all duration-500"
                         style="width:{{ $pct }}%;background:{{ $barColor }}"></div>
                </div>

                {{-- Stats 4 kolom --}}
                <div class="grid grid-cols-4 gap-2">
                    <div class="rounded-xl py-2 px-1 text-center bg-blue-50 border border-blue-100">
                        <p class="text-[16px] font-black text-[#2563EB]">{{ $rekap['hadir'] }}</p>
                        <p class="text-[10px] text-slate-500 font-bold mt-0.5">Hadir</p>
                    </div>
                    <div class="rounded-xl py-2 px-1 text-center bg-amber-50 border border-amber-100">
                        <p class="text-[16px] font-black text-[#D97706]">{{ $rekap['terlambat'] }}</p>
                        <p class="text-[10px] text-slate-500 font-bold mt-0.5">Lambat</p>
                    </div>
                    <div class="rounded-xl py-2 px-1 text-center bg-slate-100 border border-slate-200">
                        <p class="text-[16px] font-black text-slate-700">{{ $rekap['izin'] }}</p>
                        <p class="text-[10px] text-slate-500 font-bold mt-0.5">Izin</p>
                    </div>
                    <div class="rounded-xl py-2 px-1 text-center bg-red-50 border border-red-100">
                        <p class="text-[16px] font-black text-[#DC2626]">{{ $rekap['alpa'] }}</p>
                        <p class="text-[10px] text-slate-500 font-bold mt-0.5">Alpa</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white border border-dashed border-slate-300 rounded-2xl p-10 text-center">
                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 text-[#2563EB]">
                    <span class="material-symbols-outlined text-[32px]" style="font-variation-settings:'FILL' 1">group</span>
                </div>
                <p class="text-[15px] font-extrabold text-slate-800">Belum ada siswa magang</p>
                <p class="text-[12px] text-slate-400 mt-1">Data rekap akan tampil di sini secara otomatis</p>
            </div>
            @endforelse
        </div>

        {{-- ── Izin Menunggu Persetujuan ───────────────────────────── --}}
        @if($pendingIzin->count())
        <section class="pt-4">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-[18px] font-extrabold text-slate-900 tracking-tight">Izin Menunggu Persetujuan</h2>
                <span class="text-[12px] font-extrabold px-3 py-1 rounded-full bg-amber-100 text-amber-900 border border-amber-200">
                    {{ $pendingIzin->count() }} Pengajuan
                </span>
            </div>
            <div class="space-y-2.5">
                @foreach($pendingIzin as $perm)
                <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm flex items-center gap-3
                            hover:shadow-md hover:border-blue-200 transition-all">
                    <img src="{{ $perm->user->photo_url }}"
                         class="w-10 h-10 rounded-full object-cover ring-2 ring-amber-200 shrink-0"
                         alt="{{ $perm->user->name }}">
                    <div class="flex-1 min-w-0">
                        <p class="text-[14px] font-bold text-slate-900" style="word-break: break-word; overflow-wrap: break-word; white-space: normal;">{{ $perm->user->name }}</p>
                        <p class="text-[12px] text-slate-500 font-medium">
                            {{ $perm->jenis_label }}
                            <span class="text-slate-300 mx-1">·</span>
                            {{ $perm->tanggal->translatedFormat('d M Y') }}
                        </p>
                    </div>
                    <a href="{{ route('permissions.approvals') }}"
                       class="flex items-center gap-1 text-[12px] font-bold text-white bg-[#2563EB]
                              px-3.5 py-1.5 rounded-xl hover:bg-[#1D4ED8] transition-colors whitespace-nowrap shadow-sm">
                        Tinjau
                        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                    </a>
                </div>
                @endforeach
            </div>
        </section>
        @endif

    </div>
</div>
@endsection

@if(!auth()->user()?->isAdmin())
@section('bottom-nav')
    <x-bottom-nav active="reports" role="guru" />
@endsection
@endif
