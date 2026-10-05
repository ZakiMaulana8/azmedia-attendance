@extends('layouts.app')
@section('title', 'Rekap Absensi')

@section('content')
<div>

    {{-- ══════════════════════════════════════════════════════════
         HERO HEADER
    ══════════════════════════════════════════════════════════ --}}
    <section class="relative" style="background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 55%, #3B82F6 100%); border-radius: 0 0 28px 28px;">
        {{-- Ornamen --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden" style="border-radius: 0 0 28px 28px;">
            <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full" style="background:rgba(255,255,255,0.07)"></div>
            <div class="absolute -bottom-4 -left-6 w-32 h-32 rounded-full" style="background:rgba(255,255,255,0.05)"></div>
        </div>

        <div class="relative z-10 px-5 pt-6 pb-7">

            {{-- Judul --}}
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-white text-[22px]" style="font-variation-settings:'FILL' 1">bar_chart</span>
                </div>
                <div>
                    <h1 class="text-white font-extrabold text-[20px] leading-tight">Rekap Absensi</h1>
                    <p class="text-white/60 text-[12px]">Ringkasan kehadiran siswa magang</p>
                </div>
            </div>

            {{-- Summary Stats --}}
            @php
                $heroStats = [
                    ['label'=>'Hadir',     'value'=>$totalStats['hadir'],     'bg'=>'rgba(255,255,255,0.22)', 'border'=>'rgba(255,255,255,0.30)'],
                    ['label'=>'Terlambat', 'value'=>$totalStats['terlambat'], 'bg'=>'rgba(255,255,255,0.14)', 'border'=>'rgba(255,255,255,0.22)'],
                    ['label'=>'Izin',      'value'=>$totalStats['izin'],      'bg'=>'rgba(255,255,255,0.14)', 'border'=>'rgba(255,255,255,0.22)'],
                    ['label'=>'Alpa',      'value'=>$totalStats['alpa'],      'bg'=>'rgba(220,50,50,0.30)',   'border'=>'rgba(255,100,100,0.35)'],
                ];
            @endphp
            <div class="grid grid-cols-4 gap-2.5">
                @foreach($heroStats as $hs)
                <div class="rounded-2xl py-3 px-2 text-center"
                     style="background:{{ $hs['bg'] }};border:1px solid {{ $hs['border'] }}">
                    <p class="text-white font-black text-[24px] leading-none">{{ $hs['value'] }}</p>
                    <p class="text-white/65 text-[10px] font-semibold mt-1.5 leading-tight">{{ $hs['label'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════
         FILTER BULAN / TAHUN
    ══════════════════════════════════════════════════════════ --}}
    <div class="px-4 pt-5 pb-1 max-w-screen-xl mx-auto">
        <form method="GET" action="{{ route('reports.index') }}" class="flex gap-3">
            <div class="flex-1 relative">
                <select name="month"
                        class="w-full h-11 pl-4 pr-9 rounded-2xl border border-[#CBD5E1] bg-white
                               text-[#0F172A] text-[13px] font-semibold focus:outline-none
                               focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20 appearance-none
                               shadow-sm cursor-pointer transition-all"
                        onchange="this.form.submit()">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" @selected($m == $month)>
                            {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-[#94A3B8] text-[20px]">expand_more</span>
            </div>
            <div class="w-32 relative">
                <select name="year"
                        class="w-full h-11 pl-4 pr-9 rounded-2xl border border-[#CBD5E1] bg-white
                               text-[#0F172A] text-[13px] font-semibold focus:outline-none
                               focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20 appearance-none
                               shadow-sm cursor-pointer transition-all"
                        onchange="this.form.submit()">
                    @foreach(range(now()->year - 1, now()->year + 1) as $y)
                        <option value="{{ $y }}" @selected($y == $year)>{{ $y }}</option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-[#94A3B8] text-[20px]">expand_more</span>
            </div>
        </form>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         REKAP PER SISWA
    ══════════════════════════════════════════════════════════ --}}
    <div class="px-4 pt-4 pb-6 max-w-screen-xl mx-auto">

        <div class="flex justify-between items-center mb-3">
            <h2 class="text-[17px] font-extrabold text-[#0F172A]">Per Siswa</h2>
            <span class="text-[12px] text-[#94A3B8] font-medium">
                {{ \Carbon\Carbon::create(null, $month)->translatedFormat('F') }} {{ $year }}
            </span>
        </div>

        {{-- Desktop table --}}
        <div class="hidden md:block bg-white rounded-2xl border border-[#E2E8F0] shadow-sm overflow-hidden mb-5">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="bg-[#F8FAFF] border-b border-[#E2E8F0]">
                        <th class="px-4 py-3 text-left text-[11px] font-black uppercase tracking-widest text-[#94A3B8]">Nama Siswa</th>
                        <th class="px-4 py-3 text-left text-[11px] font-black uppercase tracking-widest text-[#94A3B8]">Jurusan</th>
                        <th class="px-4 py-3 text-center text-[11px] font-black uppercase tracking-widest text-[#2563EB]">Hadir</th>
                        <th class="px-4 py-3 text-center text-[11px] font-black uppercase tracking-widest text-[#F59E0B]">Terlambat</th>
                        <th class="px-4 py-3 text-center text-[11px] font-black uppercase tracking-widest text-[#64748B]">Izin</th>
                        <th class="px-4 py-3 text-center text-[11px] font-black uppercase tracking-widest text-[#EF4444]">Alpa</th>
                        <th class="px-4 py-3 text-center text-[11px] font-black uppercase tracking-widest text-[#94A3B8]">Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F1F5F9]">
                    @forelse($rekapSiswa as $rekap)
                    @php
                        $total    = $rekap['hadir'] + $rekap['izin'] + $rekap['alpa'];
                        $pct      = $total > 0 ? round(($rekap['hadir'] / $total) * 100) : 0;
                        $barColor = $pct >= 80 ? '#2563EB' : ($pct >= 60 ? '#F59E0B' : '#EF4444');
                    @endphp
                    <tr class="hover:bg-[#F8FAFF] transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $rekap['user']->photo_url }}"
                                     class="w-8 h-8 rounded-full object-cover ring-2 ring-[#DBEAFE] shrink-0"
                                     alt="{{ $rekap['user']->name }}">
                                <span class="font-semibold text-[#0F172A]">{{ $rekap['user']->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-[#64748B]">{{ $rekap['user']->jurusan ?? '—' }}</td>
                        <td class="px-4 py-3 text-center font-bold text-[#2563EB]">{{ $rekap['hadir'] }}</td>
                        <td class="px-4 py-3 text-center font-bold text-[#F59E0B]">{{ $rekap['terlambat'] }}</td>
                        <td class="px-4 py-3 text-center font-bold text-[#64748B]">{{ $rekap['izin'] }}</td>
                        <td class="px-4 py-3 text-center font-bold text-[#EF4444]">{{ $rekap['alpa'] }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-[#F1F5F9] rounded-full h-2 overflow-hidden">
                                    <div class="h-2 rounded-full transition-all"
                                         style="width:{{ $pct }}%;background:{{ $barColor }}"></div>
                                </div>
                                <span class="text-[11px] font-black w-10 text-right"
                                      style="color:{{ $barColor }}">{{ $pct }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-[#94A3B8] text-[13px]">
                            Belum ada siswa magang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="md:hidden space-y-3 mb-5">
            @forelse($rekapSiswa as $rekap)
            @php
                $total    = $rekap['hadir'] + $rekap['izin'] + $rekap['alpa'];
                $pct      = $total > 0 ? round(($rekap['hadir'] / $total) * 100) : 0;
                $barColor = $pct >= 80 ? '#2563EB' : ($pct >= 60 ? '#F59E0B' : '#EF4444');
                $pctText  = $pct >= 80 ? '#2563EB' : ($pct >= 60 ? '#F59E0B' : '#EF4444');
            @endphp
            <div class="bg-white border border-[#E2E8F0] rounded-2xl p-4 shadow-sm hover:shadow-md transition-shadow">

                {{-- Header kartu --}}
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ $rekap['user']->photo_url }}"
                         class="w-11 h-11 rounded-full object-cover ring-2 ring-[#DBEAFE] shrink-0"
                         alt="{{ $rekap['user']->name }}">
                    <div class="flex-1 min-w-0">
                        <p class="text-[14px] font-bold text-[#0F172A]" style="word-break: break-word; overflow-wrap: break-word; white-space: normal;">{{ $rekap['user']->name }}</p>
                        <p class="text-[12px] text-[#64748B]">{{ $rekap['user']->jurusan ?? '—' }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-[22px] font-black leading-none" style="color:{{ $pctText }}">{{ $pct }}%</p>
                        <p class="text-[10px] text-[#94A3B8] font-semibold mt-0.5">kehadiran</p>
                    </div>
                </div>

                {{-- Progress bar --}}
                <div class="w-full bg-[#F1F5F9] rounded-full h-2.5 mb-3 overflow-hidden">
                    <div class="h-2.5 rounded-full transition-all duration-500"
                         style="width:{{ $pct }}%;background:{{ $barColor }}"></div>
                </div>

                {{-- Stats 4 kolom --}}
                <div class="grid grid-cols-4 gap-2">
                    <div class="rounded-xl py-2 px-1 text-center" style="background:#EFF6FF">
                        <p class="text-[16px] font-black text-[#2563EB]">{{ $rekap['hadir'] }}</p>
                        <p class="text-[10px] text-[#64748B] font-semibold mt-0.5">Hadir</p>
                    </div>
                    <div class="rounded-xl py-2 px-1 text-center" style="background:#FEF3C7">
                        <p class="text-[16px] font-black text-[#D97706]">{{ $rekap['terlambat'] }}</p>
                        <p class="text-[10px] text-[#64748B] font-semibold mt-0.5">Lambat</p>
                    </div>
                    <div class="rounded-xl py-2 px-1 text-center" style="background:#F1F5F9">
                        <p class="text-[16px] font-black text-[#64748B]">{{ $rekap['izin'] }}</p>
                        <p class="text-[10px] text-[#64748B] font-semibold mt-0.5">Izin</p>
                    </div>
                    <div class="rounded-xl py-2 px-1 text-center" style="background:#FEE2E2">
                        <p class="text-[16px] font-black text-[#EF4444]">{{ $rekap['alpa'] }}</p>
                        <p class="text-[10px] text-[#64748B] font-semibold mt-0.5">Alpa</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white border border-dashed border-[#CBD5E1] rounded-2xl p-10 text-center">
                <div class="w-16 h-16 bg-[#EFF6FF] rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-[#2563EB] text-[32px]"
                          style="font-variation-settings:'FILL' 1">group</span>
                </div>
                <p class="text-[15px] font-extrabold text-[#334155]">Belum ada data siswa</p>
                <p class="text-[12px] text-[#94A3B8] mt-1">Data rekap akan tampil di sini</p>
            </div>
            @endforelse
        </div>

        {{-- ── Izin Menunggu Persetujuan ───────────────────────────── --}}
        @if($pendingIzin->count())
        <section>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-[17px] font-extrabold text-[#0F172A]">Izin Menunggu</h2>
                <span class="text-[11px] font-black px-2.5 py-1 rounded-full bg-[#FEF3C7] text-[#92400E]">
                    {{ $pendingIzin->count() }}
                </span>
            </div>
            <div class="space-y-2.5">
                @foreach($pendingIzin as $perm)
                <div class="bg-white border border-[#E2E8F0] rounded-2xl p-4 shadow-sm flex items-center gap-3
                            hover:shadow-md hover:border-[#BFDBFE] transition-all">
                    <img src="{{ $perm->user->photo_url }}"
                         class="w-10 h-10 rounded-full object-cover ring-2 ring-[#FEF3C7] shrink-0"
                         alt="{{ $perm->user->name }}">
                    <div class="flex-1 min-w-0">
                        <p class="text-[14px] font-bold text-[#0F172A]" style="word-break: break-word; overflow-wrap: break-word; white-space: normal;">{{ $perm->user->name }}</p>
                        <p class="text-[12px] text-[#64748B]">
                            {{ $perm->jenis_label }}
                            <span class="text-[#CBD5E1] mx-1">·</span>
                            {{ $perm->tanggal->translatedFormat('d M Y') }}
                        </p>
                    </div>
                    <a href="{{ route('permissions.approvals') }}"
                       class="flex items-center gap-1 text-[12px] font-bold text-white bg-[#2563EB]
                              px-3 py-1.5 rounded-full hover:bg-[#1D4ED8] transition-colors whitespace-nowrap shadow-sm">
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

@section('bottom-nav')
    <x-bottom-nav active="reports" role="guru" />
@endsection
