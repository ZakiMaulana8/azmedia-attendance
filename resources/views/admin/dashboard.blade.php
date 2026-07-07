@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('content')
<div class="p-md md:p-lg max-w-5xl mx-auto w-full space-y-lg">

    {{-- Header --}}
    <div class="mb-lg">
        <h2 class="text-headline-lg-mobile md:text-headline-lg font-bold text-on-background">
            Dashboard Admin
        </h2>
        <p class="text-body-sm text-on-surface-variant">
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </p>
    </div>

    {{-- Card Selamat Datang --}}
    <div class="bg-primary text-on-primary rounded-2xl p-lg relative overflow-hidden shadow-md">
        <div class="pointer-events-none absolute -top-12 -right-12 w-48 h-48 rounded-full"
             style="background: rgba(255,255,255,0.08)"></div>
        <div class="relative z-10 space-y-sm">
            <h3 class="text-headline-md font-bold">Selamat Datang, {{ auth()->user()->name }}!</h3>
            <p class="text-body-sm text-white/80 max-w-xl">
                Melalui panel ini, Anda dapat memantau aktivitas absensi seluruh siswa magang, guru pembimbing, dan mengelola konfigurasi geofence kehadiran.
            </p>
        </div>
    </div>

    {{-- Statistik Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-md">
        {{-- Total Siswa --}}
        <div class="bg-surface rounded-xl border border-outline-variant shadow-sm p-md flex items-center gap-md">
            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary shrink-0">
                <span class="material-symbols-outlined text-[28px]">group</span>
            </div>
            <div>
                <p class="text-body-sm text-on-surface-variant">Total Siswa</p>
                <h4 class="text-headline-md font-bold text-on-surface">{{ $totalSiswa }}</h4>
            </div>
        </div>

        {{-- Total Guru --}}
        <div class="bg-surface rounded-xl border border-outline-variant shadow-sm p-md flex items-center gap-md">
            <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary shrink-0">
                <span class="material-symbols-outlined text-[28px]">school</span>
            </div>
            <div>
                <p class="text-body-sm text-on-surface-variant">Total Guru</p>
                <h4 class="text-headline-md font-bold text-on-surface">{{ $totalGuru }}</h4>
            </div>
        </div>

        {{-- Absensi Hari Ini --}}
        <div class="bg-surface rounded-xl border border-outline-variant shadow-sm p-md flex items-center gap-md">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 shrink-0">
                <span class="material-symbols-outlined text-[28px]">fact_check</span>
            </div>
            <div>
                <p class="text-body-sm text-on-surface-variant">Absen Hari Ini</p>
                <h4 class="text-headline-md font-bold text-on-surface">{{ $totalAbsenHariIni }}</h4>
            </div>
        </div>

        {{-- Radius Geofence --}}
        <div class="bg-surface rounded-xl border border-outline-variant shadow-sm p-md flex items-center gap-md">
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
    <div class="bg-surface rounded-xl border border-outline-variant shadow-sm p-lg">
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
