{{--
    Bottom navigation bar component
    Props:
      $active - 'dashboard' | 'permission' | 'approval' | 'reports' | 'activity' | 'profile'
      $role   - 'siswa' | 'guru' | 'admin'
--}}
@props(['active' => 'dashboard', 'role' => 'siswa'])

<nav class="{{ view()->hasSection('sidebar') ? 'md:hidden' : '' }} fixed bottom-0 left-0 w-full z-50 bg-white border-t border-[#E2E8F0]"
     style="box-shadow: 0 -4px 20px rgba(37,99,235,0.08);">
    <div class="flex justify-around items-center px-2 pt-2 pb-safe w-full max-w-screen-xl mx-auto">

        @php
            $activeClass   = 'flex flex-col items-center justify-center bg-[#EFF6FF] text-[#2563EB] rounded-2xl px-2.5 sm:px-5 py-1.5 transition-all duration-200';
            $inactiveClass = 'flex flex-col items-center justify-center text-[#94A3B8] px-2 sm:px-3 py-1.5 hover:text-[#2563EB] transition-all duration-200';
        @endphp

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}" class="{{ $active === 'dashboard' ? $activeClass : $inactiveClass }}">
            <span class="material-symbols-outlined text-[22px]" @if($active === 'dashboard') style="font-variation-settings:'FILL' 1" @endif>dashboard</span>
            <span class="text-[10px] font-bold mt-0.5">Dashboard</span>
        </a>

        @if($role === 'siswa')
        {{-- Siswa: Ajukan Izin --}}
        <a href="{{ route('permissions.create') }}" class="{{ $active === 'permission' ? $activeClass : $inactiveClass }}">
            <span class="material-symbols-outlined text-[22px]" @if($active === 'permission') style="font-variation-settings:'FILL' 1" @endif>event_busy</span>
            <span class="text-[10px] font-bold mt-0.5">Izin</span>
        </a>

        @elseif($role === 'guru')
        {{-- Guru: Approval --}}
        <a href="{{ route('permissions.approvals') }}" class="{{ $active === 'approval' ? $activeClass : $inactiveClass }}">
            <span class="material-symbols-outlined text-[22px]" @if($active === 'approval') style="font-variation-settings:'FILL' 1" @endif>fact_check</span>
            <span class="text-[10px] font-bold mt-0.5">Approval</span>
        </a>

        @elseif($role === 'karyawan')
        {{-- Karyawan: Siswa PKL --}}
        <a href="{{ route('karyawan.siswa.index') }}" class="{{ $active === 'siswa' ? $activeClass : $inactiveClass }}">
            <span class="material-symbols-outlined text-[22px]" @if($active === 'siswa') style="font-variation-settings:'FILL' 1" @endif>school</span>
            <span class="text-[10px] font-bold mt-0.5">Siswa PKL</span>
        </a>
        <a href="{{ route('permissions.approvals') }}" class="{{ $active === 'approval' ? $activeClass : $inactiveClass }}">
            <span class="material-symbols-outlined text-[22px]" @if($active === 'approval') style="font-variation-settings:'FILL' 1" @endif>fact_check</span>
            <span class="text-[10px] font-bold mt-0.5">Approval</span>
        </a>
        @endif

        @if($role !== 'karyawan')
        {{-- Laporan --}}
        <a href="{{ route('reports.index') }}" class="{{ $active === 'reports' ? $activeClass : $inactiveClass }}">
            <span class="material-symbols-outlined text-[22px]" @if($active === 'reports') style="font-variation-settings:'FILL' 1" @endif>assessment</span>
            <span class="text-[10px] font-bold mt-0.5">Laporan</span>
        </a>
        @endif

        @if($role === 'siswa')
        {{-- Siswa: Kegiatan --}}
        <a href="{{ route('activity-reports.index') }}" class="{{ $active === 'activity' ? $activeClass : $inactiveClass }}">
            <span class="material-symbols-outlined text-[22px]" @if($active === 'activity') style="font-variation-settings:'FILL' 1" @endif>photo_library</span>
            <span class="text-[10px] font-bold mt-0.5">Kegiatan</span>
        </a>

        @elseif($role === 'guru')
        {{-- Guru: Kegiatan --}}
        <a href="{{ route('activity-reports.guru') }}" class="{{ $active === 'activity' ? $activeClass : $inactiveClass }}">
            <span class="material-symbols-outlined text-[22px]" @if($active === 'activity') style="font-variation-settings:'FILL' 1" @endif>photo_library</span>
            <span class="text-[10px] font-bold mt-0.5">Kegiatan</span>
        </a>

        @elseif($role === 'karyawan')
        {{-- Karyawan: Laporan Kegiatan --}}
        <a href="{{ route('karyawan.activity-reports.index') }}" class="{{ $active === 'activity' ? $activeClass : $inactiveClass }}">
            <span class="material-symbols-outlined text-[22px]" @if($active === 'activity') style="font-variation-settings:'FILL' 1" @endif>description</span>
            <span class="text-[10px] font-bold mt-0.5 text-center leading-none whitespace-nowrap">Laporan Kegiatan</span>
        </a>
        @endif

        {{-- Profil --}}
        <a href="{{ route('profile.edit') }}" class="{{ $active === 'profile' ? $activeClass : $inactiveClass }}">
            <span class="material-symbols-outlined text-[22px]" @if($active === 'profile') style="font-variation-settings:'FILL' 1" @endif>person</span>
            <span class="text-[10px] font-bold mt-0.5">Profil</span>
        </a>

    </div>
</nav>
