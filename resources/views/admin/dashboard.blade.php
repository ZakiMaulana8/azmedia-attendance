@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('content')
<div class="max-w-6xl mx-auto p-4 sm:p-6 space-y-6">

    {{-- ══════════════════════════════════════════════════════════
         HERO HEADER CARD (seragam dengan Dashboard Guru)
    ══════════════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden shadow-lg"
             style="background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 55%, #3B82F6 100%); border-radius: 24px; padding: 28px 24px;">
        <div class="pointer-events-none absolute inset-0 overflow-hidden" style="border-radius: 24px;">
            <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full" style="background:rgba(255,255,255,0.08); filter: blur(20px);"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full" style="background:rgba(255,255,255,0.06); filter: blur(16px);"></div>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <p class="text-white/80 text-[12px] font-bold uppercase tracking-wider mb-1">Administrator</p>
                <h1 class="text-white font-extrabold text-[22px] sm:text-[26px] leading-tight tracking-tight">
                    Halo, {{ auth()->user()->name }}!
                </h1>
                <p class="text-white/80 text-[13px] font-medium mt-1">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </p>
            </div>

            <div class="flex-shrink-0 text-center rounded-2xl px-4 py-3 min-w-[84px] shadow-md"
                 style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.38); backdrop-filter: blur(10px);">
                <p class="text-white/90 text-[10px] font-extrabold uppercase tracking-widest leading-none mb-1">
                    {{ \Carbon\Carbon::now()->translatedFormat('D') }}
                </p>
                <p class="text-white font-black text-[28px] leading-none tracking-tight my-0.5">
                    {{ \Carbon\Carbon::now()->day }}
                </p>
                <p class="text-white/90 text-[11px] font-bold uppercase tracking-wider leading-none">
                    {{ \Carbon\Carbon::now()->translatedFormat('M Y') }}
                </p>
            </div>
        </div>
    </section>

    {{-- Statistik Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-md">
        {{-- Total Siswa --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center gap-4 transition-all hover:shadow-md">
            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary shrink-0">
                <span class="material-symbols-outlined text-[28px]">group</span>
            </div>
            <div>
                <p class="text-body-sm text-on-surface-variant">Total Siswa</p>
                <h4 class="text-headline-md font-bold text-on-surface">{{ $totalSiswa }}</h4>
            </div>
        </div>

        {{-- Total Guru --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center gap-4 transition-all hover:shadow-md">
            <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary shrink-0">
                <span class="material-symbols-outlined text-[28px]">school</span>
            </div>
            <div>
                <p class="text-body-sm text-on-surface-variant">Total Guru</p>
                <h4 class="text-headline-md font-bold text-on-surface">{{ $totalGuru }}</h4>
            </div>
        </div>

        {{-- Absensi Hari Ini --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center gap-4 transition-all hover:shadow-md">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 shrink-0">
                <span class="material-symbols-outlined text-[28px]">fact_check</span>
            </div>
            <div>
                <p class="text-body-sm text-on-surface-variant">Absen Hari Ini</p>
                <h4 class="text-headline-md font-bold text-on-surface">{{ $totalAbsenHariIni }}</h4>
            </div>
        </div>

        {{-- Radius Geofence --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center gap-4 transition-all hover:shadow-md">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-600 shrink-0">
                <span class="material-symbols-outlined text-[28px]">distance</span>
            </div>
            <div>
                <p class="text-body-sm text-on-surface-variant">Radius Geofence</p>
                <h4 class="text-headline-md font-bold text-on-surface">{{ $radiusGeofence }}m</h4>
                <p class="text-[11px] text-on-surface-variant font-medium mt-0.5 truncate max-w-[140px]" title="{{ $geofence->nama_lokasi }}">
                    📍 {{ $geofence->nama_lokasi }}
                </p>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
        <h3 class="text-headline-md font-semibold text-on-surface mb-md">Aksi Cepat</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-sm">
            <a href="{{ route('admin.geofence') }}" 
               class="flex items-center justify-between p-md border border-outline-variant rounded-xl hover:bg-surface-container-high transition-all active:scale-95 group">
                <div class="flex items-center gap-md">
                    <span class="material-symbols-outlined text-primary text-[24px]">location_on</span>
                    <div>
                        <p class="font-bold text-on-surface text-body-lg">Atur Radius Absensi (Geofence)</p>
                        <p class="text-body-sm text-on-surface-variant">Ubah koordinat kantor dan batas radius absensi</p>
                    </div>
                </div>
                <span class="material-symbols-outlined text-outline group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
        </div>
    </div>

</div>
@endsection
