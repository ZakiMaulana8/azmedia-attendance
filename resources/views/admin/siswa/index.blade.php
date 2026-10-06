@extends('layouts.admin')
@section('title', 'Kelola Siswa Magang')

@section('content')
<div class="max-w-6xl mx-auto p-4 sm:p-6 space-y-6"
     x-data="{
        showModal: false,
        editMode: false,
        showDeleteId: null,
        form: {
            id: '', name: '', email: '', nis_nip: '', asal_sekolah: '',
            jurusan: '', phone: '', tanggal_mulai: '', tanggal_selesai: '',
            role: 'siswa', password: ''
        },
        openEdit(data) {
            this.editMode = true;
            this.form = data;
            this.showModal = true;
        }
     }">

    {{-- ════════════════════════════════════════════════════════════
         HERO HEADER
    ════════════════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden shadow-lg"
             style="background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 55%, #3B82F6 100%); border-radius: 24px; padding: 28px 24px;">
        <div class="pointer-events-none absolute inset-0 overflow-hidden" style="border-radius: 24px;">
            <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full" style="background:rgba(255,255,255,0.08); filter: blur(20px);"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full" style="background:rgba(255,255,255,0.06); filter: blur(16px);"></div>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <p class="text-white/80 text-[12px] font-bold uppercase tracking-wider mb-1">Data Master → Siswa</p>
                <h1 class="text-white font-extrabold text-[22px] sm:text-[26px] leading-tight tracking-tight">
                    Siswa Magang
                </h1>
                <p class="text-white/80 text-[13px] font-medium mt-1">
                    Kelola daftar siswa, informasi sekolah, dan periode magang.
                </p>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════
         STATISTIK CARDS
    ════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-md">
        {{-- Total Siswa --}}
        <div class="bg-white rounded-2xl border border-outline-variant/60 shadow-sm p-md flex items-center gap-md">
            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary shrink-0">
                <span class="material-symbols-outlined text-[26px]" style="font-variation-settings:'FILL' 1">group</span>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Total Siswa</p>
                <p class="text-[28px] font-black text-on-surface leading-none" style="letter-spacing: -0.03em;">
                    {{ $totalSiswa }}
                </p>
            </div>
        </div>

        {{-- Aktif Magang --}}
        <div class="bg-white rounded-2xl border border-outline-variant/60 shadow-sm p-md flex items-center gap-md">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: rgba(16,185,129,0.12)">
                <span class="material-symbols-outlined text-[26px] text-emerald-600" style="font-variation-settings:'FILL' 1">badge</span>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Aktif</p>
                <p class="text-[28px] font-black text-emerald-600 leading-none" style="letter-spacing: -0.03em;">
                    {{ $activeSiswa }}
                </p>
            </div>
        </div>

        {{-- Sekolah --}}
        <div class="bg-white rounded-2xl border border-outline-variant/60 shadow-sm p-md flex items-center gap-md">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: rgba(133,83,0,0.10)">
                <span class="material-symbols-outlined text-[26px] text-secondary" style="font-variation-settings:'FILL' 1">school</span>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Sekolah</p>
                <p class="text-[28px] font-black text-secondary leading-none" style="letter-spacing: -0.03em;">
                    {{ $schoolCount }}
                </p>
            </div>
        </div>

        {{-- Total Absensi --}}
        <div class="bg-white rounded-2xl border border-outline-variant/60 shadow-sm p-md flex items-center gap-md">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: rgba(99,102,241,0.10)">
                <span class="material-symbols-outlined text-[26px] text-indigo-600" style="font-variation-settings:'FILL' 1">fact_check</span>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Total Absen</p>
                <p class="text-[28px] font-black text-indigo-600 leading-none" style="letter-spacing: -0.03em;">
                    {{ $totalAbsensi }}
                </p>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════
         FLASH NOTIFICATIONS
    ════════════════════════════════════════════════════════════ --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-md py-sm rounded-xl flex items-center gap-sm shadow-sm">
            <span class="material-symbols-outlined text-[20px] text-emerald-600" style="font-variation-settings:'FILL' 1">check_circle</span>
            <span class="text-[13px] font-semibold">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-md py-sm rounded-xl flex items-center gap-sm shadow-sm">
            <span class="material-symbols-outlined text-[20px] text-red-600" style="font-variation-settings:'FILL' 1">error</span>
            <span class="text-[13px] font-semibold">{{ session('error') }}</span>
        </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════
         SEARCH & FILTER BAR
    ════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-outline-variant/60 shadow-sm p-md">
        <form method="GET" action="{{ route('admin.siswa.index') }}" class="flex gap-sm">
            @if(request('view'))
                <input type="hidden" name="view" value="{{ request('view') }}">
            @endif
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama, email, NIS, atau sekolah..."
                       class="w-full pl-xl pr-md py-sm bg-surface-container-low border border-outline-variant rounded-xl text-[13px] focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
            </div>
            <button type="submit"
                    class="bg-primary hover:bg-primary/90 text-white font-semibold px-md py-sm rounded-xl transition-all active:scale-95 flex items-center gap-xs whitespace-nowrap">
                <span class="material-symbols-outlined text-[18px]">search</span>
                <span class="hidden sm:inline">Cari</span>
            </button>
            @if(request('search'))
                <a href="{{ route('admin.siswa.index', array_filter(['view' => request('view')])) }}"
                   class="bg-surface-container-high hover:bg-surface-container text-on-surface-variant font-semibold px-md py-sm rounded-xl flex items-center gap-xs transition-all whitespace-nowrap">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                    <span class="hidden sm:inline">Reset</span>
                </a>
            @endif
        </form>
    </div>

    {{-- ════════════════════════════════════════════════════════════
         FILTER TAB: SISWA vs SOFT DELETED
    ════════════════════════════════════════════════════════════ --}}
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.siswa.index', array_filter(['search' => request('search'), 'view' => 'active'])) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-[13px] font-bold transition-all {{ ($currentView ?? 'active') === 'active' ? 'bg-primary text-white shadow-sm' : 'bg-white text-on-surface-variant hover:bg-surface-container-high border border-outline-variant/60' }}">
            <span class="material-symbols-outlined text-[18px]">school</span>
            <span>Siswa</span>
            <span class="text-[11px] px-2 py-0.5 rounded-full {{ ($currentView ?? 'active') === 'active' ? 'bg-white/20 text-white' : 'bg-surface-container text-on-surface-variant' }}">
                {{ $totalSiswa }}
            </span>
        </a>

        <a href="{{ route('admin.siswa.index', array_filter(['search' => request('search'), 'view' => 'deleted'])) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-[13px] font-bold transition-all {{ ($currentView ?? 'active') === 'deleted' ? 'bg-red-600 text-white shadow-sm' : 'bg-white text-on-surface-variant hover:bg-surface-container-high border border-outline-variant/60' }}">
            <span class="material-symbols-outlined text-[18px]">delete</span>
            <span>Soft Deleted</span>
            <span class="text-[11px] px-2 py-0.5 rounded-full {{ ($currentView ?? 'active') === 'deleted' ? 'bg-white/20 text-white' : 'bg-red-50 text-red-600' }}">
                {{ $deletedSiswa }}
            </span>
        </a>
    </div>

    {{-- ════════════════════════════════════════════════════════════
         DATA TABLE
    ════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-outline-variant/60 shadow-sm overflow-hidden">

        {{-- Table header bar --}}
        <div class="px-lg py-md border-b border-outline-variant/50 flex items-center justify-between">
            <div class="flex items-center gap-sm">
                @if(($currentView ?? 'active') === 'deleted')
                    <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-red-600 text-[18px]" style="font-variation-settings:'FILL' 1">delete</span>
                    </div>
                    <div>
                        <p class="text-[14px] font-bold text-on-surface">Daftar Siswa Soft Deleted</p>
                        <p class="text-[11px] text-on-surface-variant">
                            {{ $siswas->total() }} siswa soft deleted
                            @if(request('search')) — hasil pencarian "{{ request('search') }}" @endif
                        </p>
                    </div>
                @else
                    <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-[18px]" style="font-variation-settings:'FILL' 1">person</span>
                    </div>
                    <div>
                        <p class="text-[14px] font-bold text-on-surface">Daftar Siswa Magang</p>
                        <p class="text-[11px] text-on-surface-variant">
                            {{ $siswas->total() }} siswa terdaftar
                            @if(request('search')) — hasil pencarian "{{ request('search') }}" @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low/70 border-b border-outline-variant/50">
                    <tr>
                        <th class="py-sm px-lg text-[11px] font-bold uppercase tracking-widest text-on-surface-variant">Siswa</th>
                        <th class="py-sm px-md text-[11px] font-bold uppercase tracking-widest text-on-surface-variant">NIS / Telepon</th>
                        <th class="py-sm px-md text-[11px] font-bold uppercase tracking-widest text-on-surface-variant">Sekolah & Jurusan</th>
                        <th class="py-sm px-md text-[11px] font-bold uppercase tracking-widest text-on-surface-variant">Periode Magang</th>
                        <th class="py-sm px-md text-[11px] font-bold uppercase tracking-widest text-on-surface-variant text-center">Status</th>
                        <th class="py-sm px-md text-[11px] font-bold uppercase tracking-widest text-on-surface-variant text-center">Absensi</th>
                        <th class="py-sm px-md text-[11px] font-bold uppercase tracking-widest text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @forelse($siswas as $siswa)
                        <tr class="hover:bg-primary/[0.02] transition-colors group">
                            {{-- Avatar + Nama --}}
                            <td class="py-md px-lg">
                                <div class="flex items-center gap-md">
                                    <div class="relative shrink-0">
                                        <img src="{{ $siswa->photo_url }}"
                                             alt="{{ $siswa->name }}"
                                             class="w-10 h-10 rounded-full object-cover ring-2 ring-outline-variant/30">
                                        @if(($currentView ?? 'active') === 'deleted')
                                            <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-red-500 border-2 border-white"
                                                  title="Soft Deleted"></span>
                                        @else
                                            <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full {{ $siswa->is_active ? 'bg-emerald-500' : 'bg-slate-400' }} border-2 border-white"
                                                  title="{{ $siswa->is_active ? 'Akun Aktif' : 'Akun Nonaktif' }}"></span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[13px] font-bold text-on-surface truncate">{{ $siswa->name }}</p>
                                        <p class="text-[12px] text-on-surface-variant truncate">{{ $siswa->email }}</p>
                                    </div>
                                </div>
                            </td>
                            {{-- NIS / Telepon --}}
                            <td class="py-md px-md">
                                <p class="text-[13px] font-semibold text-on-surface">{{ $siswa->nis_nip ?: '—' }}</p>
                                <p class="text-[12px] text-on-surface-variant mt-0.5">{{ $siswa->phone ?: '—' }}</p>
                            </td>
                            {{-- Sekolah --}}
                            <td class="py-md px-md">
                                <p class="text-[13px] font-semibold text-on-surface max-w-[180px] truncate">{{ $siswa->asal_sekolah ?: '—' }}</p>
                                <span class="inline-block mt-1 text-[11px] font-bold text-primary bg-primary/10 px-2 py-0.5 rounded-full">
                                    {{ $siswa->jurusan ?: '—' }}
                                </span>
                            </td>
                            {{-- Periode --}}
                            <td class="py-md px-md">
                                @if($siswa->tanggal_mulai && $siswa->tanggal_selesai)
                                    @php
                                        $isActive = $siswa->tanggal_selesai->isFuture();
                                    @endphp
                                    <div class="flex items-center gap-1.5 mb-1">
                                        <span class="w-2 h-2 rounded-full {{ $isActive ? 'bg-emerald-500' : 'bg-outline' }}"></span>
                                        <span class="text-[11px] font-bold {{ $isActive ? 'text-emerald-700' : 'text-on-surface-variant' }}">
                                            {{ $isActive ? 'Aktif' : 'Selesai' }}
                                        </span>
                                    </div>
                                    <p class="text-[12px] text-on-surface-variant">
                                        {{ $siswa->tanggal_mulai->format('d M Y') }}
                                    </p>
                                    <p class="text-[12px] text-on-surface-variant">
                                        s/d {{ $siswa->tanggal_selesai->format('d M Y') }}
                                    </p>
                                @else
                                    <span class="text-[12px] text-outline italic">Belum diatur</span>
                                @endif
                            </td>
                            {{-- Status Akun --}}
                            <td class="py-md px-md text-center">
                                @if(($currentView ?? 'active') === 'deleted')
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-red-800 bg-red-50 border border-red-200 px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                                        Soft Deleted
                                    </span>
                                @elseif($siswa->is_active)
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-800 bg-emerald-50 border border-emerald-200/80 px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-slate-600 bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            {{-- Total Absen --}}
                            <td class="py-md px-md text-center">
                                <div class="inline-flex flex-col items-center">
                                    <span class="text-[22px] font-black text-primary leading-none" style="letter-spacing: -0.02em;">
                                        {{ $siswa->attendances_count }}
                                    </span>
                                    <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wide mt-0.5">hari</span>
                                </div>
                            </td>
                            {{-- Aksi --}}
                            <td class="py-md px-md text-right">
                                <div class="flex items-center justify-end gap-1.5 opacity-80 group-hover:opacity-100 transition-opacity">
                                    @if(($currentView ?? 'active') === 'deleted')
                                        {{-- Tab Soft Deleted: Tombol Restore --}}
                                        <form method="POST" action="{{ route('admin.users.restore', $siswa) }}"
                                              onsubmit="return confirm('Restore akun siswa {{ addslashes($siswa->name) }}?')"
                                              class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-[12px] font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200/80 transition-all active:scale-95"
                                                    title="Restore">
                                                <span class="material-symbols-outlined text-[15px]">restore_from_trash</span>
                                                <span>Restore</span>
                                            </button>
                                        </form>
                                    @else
                                        {{-- Tab Normal: Tombol Toggle Status (Aktifkan / Nonaktifkan) --}}
                                        @if($siswa->is_active)
                                            @if(auth()->id() === $siswa->id)
                                                <button type="button"
                                                        disabled
                                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-[12px] font-bold text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed opacity-60"
                                                        title="Anda tidak dapat menonaktifkan akun Anda sendiri">
                                                    <span class="material-symbols-outlined text-[15px]">block</span>
                                                    <span>Nonaktifkan</span>
                                                </button>
                                            @else
                                                <form method="POST" action="{{ route('admin.users.deactivate', $siswa) }}"
                                                      onsubmit="return confirm('Nonaktifkan akun siswa {{ addslashes($siswa->name) }}? Siswa tidak dapat login ke aplikasi.')"
                                                      class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-[12px] font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200/80 transition-all active:scale-95"
                                                            title="Nonaktifkan akun siswa">
                                                        <span class="material-symbols-outlined text-[15px]">person_off</span>
                                                        <span>Nonaktifkan</span>
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <form method="POST" action="{{ route('admin.users.activate', $siswa) }}"
                                                  onsubmit="return confirm('Aktifkan kembali akun siswa {{ addslashes($siswa->name) }}?')"
                                                  class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-[12px] font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200/80 transition-all active:scale-95"
                                                        title="Aktifkan akun siswa">
                                                    <span class="material-symbols-outlined text-[15px]">check_circle</span>
                                                    <span>Aktifkan</span>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Tombol Edit Modal --}}
                                        <button @click="openEdit({
                                                    id: '{{ $siswa->id }}',
                                                    name: '{{ addslashes($siswa->name) }}',
                                                    email: '{{ addslashes($siswa->email) }}',
                                                    nis_nip: '{{ $siswa->nis_nip }}',
                                                    asal_sekolah: '{{ addslashes($siswa->asal_sekolah) }}',
                                                    jurusan: '{{ addslashes($siswa->jurusan) }}',
                                                    phone: '{{ $siswa->phone }}',
                                                    tanggal_mulai: '{{ $siswa->tanggal_mulai?->format('Y-m-d') }}',
                                                    tanggal_selesai: '{{ $siswa->tanggal_selesai?->format('Y-m-d') }}',
                                                    role: 'siswa',
                                                    password: ''
                                                })"
                                                class="w-8 h-8 rounded-xl flex items-center justify-center text-primary bg-primary/5 hover:bg-primary/15 transition-all active:scale-90"
                                                title="Edit data siswa">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </button>

                                        {{-- Tombol Soft Delete --}}
                                        @if(auth()->id() === $siswa->id)
                                            <button type="button"
                                                    disabled
                                                    class="w-8 h-8 rounded-xl flex items-center justify-center text-slate-300 bg-slate-100 cursor-not-allowed opacity-60"
                                                    title="Anda tidak dapat melakukan Soft Delete pada akun Anda sendiri">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        @else
                                            <form method="POST" action="{{ route('admin.users.destroy', $siswa) }}"
                                                  onsubmit="return confirm('Lakukan Soft Delete pada akun siswa {{ addslashes($siswa->name) }}?')"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="w-8 h-8 rounded-xl flex items-center justify-center text-red-600 bg-red-50 hover:bg-red-100 transition-all active:scale-90"
                                                        title="Soft Delete">
                                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-2xl text-center">
                                <div class="flex flex-col items-center gap-md">
                                    <div class="w-16 h-16 rounded-2xl bg-surface-container flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[36px] text-outline">{{ ($currentView ?? 'active') === 'deleted' ? 'delete_sweep' : 'person_search' }}</span>
                                    </div>
                                    <div>
                                        <p class="text-[14px] font-bold text-on-surface mb-1">
                                            @if(request('search'))
                                                Siswa tidak ditemukan
                                            @elseif(($currentView ?? 'active') === 'deleted')
                                                Tidak ada data siswa soft deleted
                                            @else
                                                Belum ada data siswa magang
                                            @endif
                                        </p>
                                        @if(request('search'))
                                            <p class="text-[12px] text-on-surface-variant">
                                                Coba ubah kata kunci pencarian.
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($siswas->hasPages())
            <div class="px-lg py-md border-t border-outline-variant/50 bg-surface-container-low/40">
                {{ $siswas->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    {{-- ════════════════════════════════════════════════════════════
         MODAL FORM (EDIT)
    ════════════════════════════════════════════════════════════ --}}
    <div x-show="showModal"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-md bg-black/50 backdrop-blur-sm">

        <div @click.away="showModal = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="bg-white rounded-2xl border border-outline-variant/60 max-w-xl w-full shadow-2xl max-h-[90vh] overflow-y-auto">

            {{-- Modal Header --}}
            <div class="px-lg py-md border-b border-outline-variant/50 flex items-center justify-between sticky top-0 bg-white z-10">
                <div class="flex items-center gap-sm">
                    <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-[18px]" style="font-variation-settings:'FILL' 1">edit</span>
                    </div>
                    <div>
                        <h3 class="text-[15px] font-extrabold text-on-surface">Edit Data Siswa</h3>
                        <p class="text-[11px] text-on-surface-variant">Perbarui informasi siswa</p>
                    </div>
                </div>
                <button @click="showModal = false"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-outline hover:text-on-surface hover:bg-surface-container transition-all">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form :action="'/admin/users/' + form.id"
                  method="POST"
                  class="p-lg space-y-md">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <input type="hidden" name="role" value="siswa">

                {{-- Nama --}}
                <div>
                    <label class="block text-[12px] font-bold text-on-surface mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" x-model="form.name" required
                           placeholder="Contoh: Budi Santoso"
                           class="w-full px-md py-sm bg-surface-container-low border border-outline-variant rounded-xl text-[13px] focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                </div>

                {{-- Email + NIS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface mb-1.5">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" x-model="form.email" required
                               placeholder="budi@email.com"
                               class="w-full px-md py-sm bg-surface-container-low border border-outline-variant rounded-xl text-[13px] focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface mb-1.5">NIS</label>
                        <input type="text" name="nis_nip" x-model="form.nis_nip"
                               placeholder="Nomor Induk Siswa"
                               class="w-full px-md py-sm bg-surface-container-low border border-outline-variant rounded-xl text-[13px] focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>
                </div>

                {{-- Sekolah + Jurusan --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface mb-1.5">Asal Sekolah</label>
                        <input type="text" name="asal_sekolah" x-model="form.asal_sekolah"
                               placeholder="SMKN 1 Surabaya"
                               class="w-full px-md py-sm bg-surface-container-low border border-outline-variant rounded-xl text-[13px] focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface mb-1.5">Jurusan</label>
                        <input type="text" name="jurusan" x-model="form.jurusan"
                               placeholder="RPL / TKJ / TI"
                               class="w-full px-md py-sm bg-surface-container-low border border-outline-variant rounded-xl text-[13px] focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>
                </div>

                {{-- Periode Magang --}}
                <div>
                    <label class="block text-[12px] font-bold text-on-surface mb-1.5">Periode Magang</label>
                    <div class="grid grid-cols-2 gap-md">
                        <div>
                            <p class="text-[11px] text-on-surface-variant mb-1">Tanggal Mulai</p>
                            <input type="date" name="tanggal_mulai" x-model="form.tanggal_mulai"
                                   class="w-full px-md py-sm bg-surface-container-low border border-outline-variant rounded-xl text-[13px] focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        </div>
                        <div>
                            <p class="text-[11px] text-on-surface-variant mb-1">Tanggal Selesai</p>
                            <input type="date" name="tanggal_selesai" x-model="form.tanggal_selesai"
                                   class="w-full px-md py-sm bg-surface-container-low border border-outline-variant rounded-xl text-[13px] focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        </div>
                    </div>
                </div>

                {{-- Telepon --}}
                <div>
                    <label class="block text-[12px] font-bold text-on-surface mb-1.5">Nomor Telepon</label>
                    <input type="text" name="phone" x-model="form.phone"
                           placeholder="081234567890"
                           class="w-full px-md py-sm bg-surface-container-low border border-outline-variant rounded-xl text-[13px] focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-[12px] font-bold text-on-surface mb-1.5">
                        Password
                        <span x-show="editMode" class="text-outline font-normal">(Kosongkan jika tidak diubah)</span>
                    </label>
                    <input type="password" name="password" x-model="form.password"
                           :placeholder="editMode ? '••••••••' : 'Default: password'"
                           class="w-full px-md py-sm bg-surface-container-low border border-outline-variant rounded-xl text-[13px] focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                </div>

                {{-- Form Actions --}}
                <div class="flex justify-end gap-sm pt-sm border-t border-outline-variant/50">
                    <button type="button" @click="showModal = false"
                            class="px-md py-sm bg-surface-container text-on-surface-variant font-semibold rounded-xl hover:bg-surface-container-high transition-all active:scale-95">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-md py-sm bg-primary text-white font-bold rounded-xl hover:bg-primary/90 shadow-md transition-all active:scale-95 flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
