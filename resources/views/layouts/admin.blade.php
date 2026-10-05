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
@endsection

@section('content')
    @yield('content')
@endsection
