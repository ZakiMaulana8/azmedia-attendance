@extends('layouts.app')

@section('sidebar')
<nav class="flex flex-col gap-xs mt-xs">
    {{-- 1. Dashboard --}}
    <a href="{{ route('dashboard') }}"
       class="flex items-center gap-md p-sm rounded-xl {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-[#2563EB] font-bold border border-blue-100' : 'text-slate-600 hover:bg-slate-50' }} transition-all active:scale-95">
        <span class="material-symbols-outlined" @if(request()->routeIs('dashboard')) style="font-variation-settings:'FILL' 1" @endif>dashboard</span>
        <span class="text-body-lg">Dashboard</span>
    </a>

    {{-- 2. Data Master --}}
    <p class="text-label-md text-slate-400 px-sm py-xs uppercase tracking-wider mt-md font-bold text-[11px]">Data Master</p>
    <a href="{{ route('admin.siswa.index') }}"
       class="flex items-center gap-md p-sm rounded-xl {{ request()->routeIs('admin.siswa.*') ? 'bg-blue-50 text-[#2563EB] font-bold border border-blue-100' : 'text-slate-600 hover:bg-slate-50' }} transition-all active:scale-95">
        <span class="material-symbols-outlined" @if(request()->routeIs('admin.siswa.*')) style="font-variation-settings:'FILL' 1" @endif>person</span>
        <span class="text-body-lg">Siswa Magang</span>
    </a>
    <a href="{{ route('admin.guru.index') }}"
       class="flex items-center gap-md p-sm rounded-xl {{ request()->routeIs('admin.guru.*') ? 'bg-blue-50 text-[#2563EB] font-bold border border-blue-100' : 'text-slate-600 hover:bg-slate-50' }} transition-all active:scale-95">
        <span class="material-symbols-outlined" @if(request()->routeIs('admin.guru.*')) style="font-variation-settings:'FILL' 1" @endif>school</span>
        <span class="text-body-lg">Guru Pembimbing</span>
    </a>
    <a href="{{ route('admin.karyawan.index') }}"
       class="flex items-center gap-md p-sm rounded-xl {{ request()->routeIs('admin.karyawan.*') ? 'bg-blue-50 text-[#2563EB] font-bold border border-blue-100' : 'text-slate-600 hover:bg-slate-50' }} transition-all active:scale-95">
        <span class="material-symbols-outlined" @if(request()->routeIs('admin.karyawan.*')) style="font-variation-settings:'FILL' 1" @endif>badge</span>
        <span class="text-body-lg">Karyawan</span>
    </a>

    {{-- 3. Operasional --}}
    <p class="text-label-md text-slate-400 px-sm py-xs uppercase tracking-wider mt-md font-bold text-[11px]">Operasional</p>
    <a href="{{ route('admin.geofence') }}"
       class="flex items-center gap-md p-sm rounded-xl {{ request()->routeIs('admin.geofence*') ? 'bg-blue-50 text-[#2563EB] font-bold border border-blue-100' : 'text-slate-600 hover:bg-slate-50' }} transition-all active:scale-95">
        <span class="material-symbols-outlined" @if(request()->routeIs('admin.geofence*')) style="font-variation-settings:'FILL' 1" @endif>location_on</span>
        <span class="text-body-lg">Geofence</span>
    </a>
    <a href="{{ route('reports.index') }}"
       class="flex items-center gap-md p-sm rounded-xl {{ request()->routeIs('reports.*') ? 'bg-blue-50 text-[#2563EB] font-bold border border-blue-100' : 'text-slate-600 hover:bg-slate-50' }} transition-all active:scale-95">
        <span class="material-symbols-outlined" @if(request()->routeIs('reports.*')) style="font-variation-settings:'FILL' 1" @endif>assessment</span>
        <span class="text-body-lg">Rekap Absensi</span>
    </a>
    <a href="{{ route('permissions.approvals') }}"
       class="flex items-center gap-md p-sm rounded-xl {{ request()->routeIs('permissions.approvals*', 'permissions.approve*') ? 'bg-blue-50 text-[#2563EB] font-bold border border-blue-100' : 'text-slate-600 hover:bg-slate-50' }} transition-all active:scale-95">
        <span class="material-symbols-outlined" @if(request()->routeIs('permissions.approvals*', 'permissions.approve*')) style="font-variation-settings:'FILL' 1" @endif>fact_check</span>
        <span class="text-body-lg">Pengajuan Izin</span>
    </a>
    <a href="{{ route('activity-reports.guru') }}"
       class="flex items-center gap-md p-sm rounded-xl {{ request()->routeIs('activity-reports.guru*', 'activity-reports.show*') ? 'bg-blue-50 text-[#2563EB] font-bold border border-blue-100' : 'text-slate-600 hover:bg-slate-50' }} transition-all active:scale-95">
        <span class="material-symbols-outlined" @if(request()->routeIs('activity-reports.guru*', 'activity-reports.show*')) style="font-variation-settings:'FILL' 1" @endif>photo_library</span>
        <span class="text-body-lg">Laporan Kegiatan Siswa</span>
    </a>

    {{-- 4. Akun --}}
    <p class="text-label-md text-slate-400 px-sm py-xs uppercase tracking-wider mt-md font-bold text-[11px]">Akun</p>
    <a href="{{ route('profile.edit') }}"
       class="flex items-center gap-md p-sm rounded-xl {{ request()->routeIs('profile.*') ? 'bg-blue-50 text-[#2563EB] font-bold border border-blue-100' : 'text-slate-600 hover:bg-slate-50' }} transition-all active:scale-95">
        <span class="material-symbols-outlined" @if(request()->routeIs('profile.*')) style="font-variation-settings:'FILL' 1" @endif>person</span>
        <span class="text-body-lg">Profil Saya</span>
    </a>
</nav>

{{-- 5. Sesi --}}
<div class="mt-auto pt-md border-t border-outline-variant/50">
    <p class="text-label-md text-slate-400 px-sm py-xs uppercase tracking-wider font-bold text-[11px] mb-xs">Sesi</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
                class="w-full flex items-center gap-md p-sm rounded-xl border border-red-200 text-red-600 font-bold
                       hover:bg-red-50 active:scale-95 transition-all">
            <span class="material-symbols-outlined">logout</span>
            <span class="text-body-lg">Keluar Sesi</span>
        </button>
    </form>
</div>
@endsection

@section('content')
    @yield('content')
@endsection
