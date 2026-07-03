{{--
    Bottom navigation bar component
    Props:
      $active - 'dashboard' | 'permission' | 'approval' | 'reports' | 'profile'
      $role   - 'siswa' | 'guru' | 'admin'
--}}
@props(['active' => 'dashboard', 'role' => 'siswa'])

<nav class="md:hidden fixed bottom-0 left-0 w-full z-50 flex justify-around items-center
            px-md py-sm pb-safe bg-surface border-t border-outline-variant">

    @php
        $activeClass   = 'flex flex-col items-center justify-center bg-primary-container text-on-primary-container rounded-full px-lg py-1 transition-all active:scale-90 duration-200';
        $inactiveClass = 'flex flex-col items-center justify-center text-on-surface-variant p-2 hover:bg-surface-container-high transition-all active:scale-90 duration-200';
    @endphp

    <a href="{{ route('dashboard') }}" class="{{ $active === 'dashboard' ? $activeClass : $inactiveClass }}">
        <span class="material-symbols-outlined" @if($active === 'dashboard') style="font-variation-settings:'FILL' 1" @endif>dashboard</span>
        <span class="text-label-md">Dashboard</span>
    </a>

    @if($role === 'siswa')
    {{-- Siswa: ajukan izin --}}
    <a href="{{ route('permissions.create') }}" class="{{ $active === 'permission' ? $activeClass : $inactiveClass }}">
        <span class="material-symbols-outlined" @if($active === 'permission') style="font-variation-settings:'FILL' 1" @endif>event_busy</span>
        <span class="text-label-md">Izin</span>
    </a>
    @elseif($role === 'guru')
    {{-- Guru: daftar approval izin siswa --}}
    <a href="{{ route('permissions.approvals') }}" class="{{ $active === 'approval' ? $activeClass : $inactiveClass }}">
        <span class="material-symbols-outlined" @if($active === 'approval') style="font-variation-settings:'FILL' 1" @endif>fact_check</span>
        <span class="text-label-md">Approval</span>
    </a>
    @endif

    <a href="{{ route('reports.index') }}" class="{{ $active === 'reports' ? $activeClass : $inactiveClass }}">
        <span class="material-symbols-outlined" @if($active === 'reports') style="font-variation-settings:'FILL' 1" @endif>assessment</span>
        <span class="text-label-md">Laporan</span>
    </a>

    @if($role === 'siswa')
    <a href="{{ route('activity-reports.index') }}" class="{{ $active === 'activity' ? $activeClass : $inactiveClass }}">
        <span class="material-symbols-outlined" @if($active === 'activity') style="font-variation-settings:'FILL' 1" @endif>photo_library</span>
        <span class="text-label-md">Kegiatan</span>
    </a>
    @elseif($role === 'guru')
    <a href="{{ route('activity-reports.guru') }}" class="{{ $active === 'activity' ? $activeClass : $inactiveClass }}">
        <span class="material-symbols-outlined" @if($active === 'activity') style="font-variation-settings:'FILL' 1" @endif>photo_library</span>
        <span class="text-label-md">Kegiatan</span>
    </a>
    @endif

    <a href="{{ route('profile.edit') }}" class="{{ $active === 'profile' ? $activeClass : $inactiveClass }}">
        <span class="material-symbols-outlined" @if($active === 'profile') style="font-variation-settings:'FILL' 1" @endif>person</span>
        <span class="text-label-md">Profil</span>
    </a>
</nav>
