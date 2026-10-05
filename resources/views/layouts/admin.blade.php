@extends('layouts.app')

@section('sidebar')
<nav class="flex flex-col gap-xs mt-xs">
    <a href="{{ route('dashboard') }}"
       class="flex items-center gap-md p-sm rounded-xl {{ request()->routeIs('dashboard') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high' }} transition-all active:scale-95">
        <span class="material-symbols-outlined" @if(request()->routeIs('dashboard')) style="font-variation-settings:'FILL' 1" @endif>dashboard</span>
        <span class="text-body-lg">Dashboard</span>
    </a>

    <p class="text-label-md text-outline px-sm py-xs uppercase tracking-wider mt-md">Data Master</p>
    <a href="{{ route('admin.siswa.index') }}"
       class="flex items-center gap-md p-sm rounded-xl {{ request()->routeIs('admin.siswa.*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high' }} transition-all active:scale-95">
        <span class="material-symbols-outlined" @if(request()->routeIs('admin.siswa.*')) style="font-variation-settings:'FILL' 1" @endif>person</span>
        <span class="text-body-lg">Siswa Magang</span>
    </a>
    <a href="{{ route('admin.guru.index') }}"
       class="flex items-center gap-md p-sm rounded-xl {{ request()->routeIs('admin.guru.*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high' }} transition-all active:scale-95">
        <span class="material-symbols-outlined" @if(request()->routeIs('admin.guru.*')) style="font-variation-settings:'FILL' 1" @endif>school</span>
        <span class="text-body-lg">Guru Pembimbing</span>
    </a>

    <p class="text-label-md text-outline px-sm py-xs uppercase tracking-wider mt-md">Pengaturan</p>
    <a href="{{ route('admin.geofence') }}"
       class="flex items-center gap-md p-sm rounded-xl {{ request()->routeIs('admin.geofence*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high' }} transition-all active:scale-95">
        <span class="material-symbols-outlined" @if(request()->routeIs('admin.geofence*')) style="font-variation-settings:'FILL' 1" @endif>location_on</span>
        <span class="text-body-lg">Geofence</span>
    </a>
</nav>

{{-- Aksi akun: Keluar Sesi (route logout bawaan, POST + CSRF) --}}
<form method="POST" action="{{ route('logout') }}" class="mt-auto pt-md border-t border-outline-variant/50">
    @csrf
    <button type="submit"
            class="w-full flex items-center gap-md p-sm rounded-xl border border-red-200 text-red-600 font-bold
                   hover:bg-red-50 active:scale-95 transition-all">
        <span class="material-symbols-outlined">logout</span>
        <span class="text-body-lg">Keluar Sesi</span>
    </button>
</form>
@endsection

@section('content')
    @yield('content')
@endsection
