@extends('layouts.app')
@section('title', 'Riwayat Izin')

@section('content')
<div class="max-w-6xl mx-auto p-4 sm:p-6 space-y-6">

    {{-- ── Header ─────────────────────────────────────────────────── --}}
    <section class="relative overflow-hidden shadow-lg"
             style="background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 55%, #3B82F6 100%); border-radius: 24px; padding: 28px 24px;">
        <div class="pointer-events-none absolute inset-0 overflow-hidden" style="border-radius: 24px;">
            <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full" style="background:rgba(255,255,255,0.08); filter: blur(20px);"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full" style="background:rgba(255,255,255,0.06); filter: blur(16px);"></div>
        </div>

        <div class="relative z-10 flex items-center justify-between gap-4">
            <div>
                <p class="text-white/80 text-[12px] font-bold uppercase tracking-wider mb-1">Manajemen Izin</p>
                <h1 class="text-white font-extrabold text-[22px] sm:text-[26px] leading-tight tracking-tight">
                    Riwayat Izin
                </h1>
                <p class="text-white/80 text-[13px] font-medium mt-1">Semua pengajuan izin Anda</p>
            </div>
            <a href="{{ route('permissions.create') }}"
               class="flex-shrink-0 flex items-center gap-1.5 rounded-2xl px-4 py-2.5 font-bold text-[13px]
                      active:scale-95 transition-all duration-150 text-white hover:bg-white/30"
               style="background: rgba(255,255,255,0.20); border: 1px solid rgba(255,255,255,0.38); backdrop-filter: blur(10px);">
                <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1">add</span>
                Buat Izin
            </a>
        </div>
    </section>

    {{-- ── List ─────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">

        @forelse($permissions as $perm)
        @php
            // Warna ikon berdasarkan jenis
            $iconBg  = match($perm->jenis) {
                'sakit'      => 'background: rgba(133,83,0,0.10)',
                'dispensasi' => 'background: rgba(37,99,235,0.10)',
                default      => 'background: rgba(164,58,58,0.10)',
            };
            $iconColor = match($perm->jenis) {
                'sakit'      => 'text-secondary',
                'dispensasi' => 'text-primary',
                default      => 'text-tertiary',
            };
            $icon = match($perm->jenis) {
                'sakit'      => 'medical_services',
                'dispensasi' => 'event_available',
                default      => 'info',
            };
            // Badge status
            $badgeBg   = match($perm->status) {
                'disetujui' => 'bg-blue-100 text-blue-700',
                'ditolak'   => 'bg-red-100 text-red-600',
                default     => 'bg-amber-100 text-amber-700',
            };
            $dotColor  = match($perm->status) {
                'disetujui' => 'bg-blue-600',
                'ditolak'   => 'bg-red-500',
                default     => 'bg-amber-500',
            };
        @endphp

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden transition-all hover:shadow-md">
            {{-- Baris atas: ikon + info + badge --}}
            <div class="flex items-center gap-md px-md pt-md pb-sm">
                {{-- Ikon jenis --}}
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center flex-shrink-0 {{ $iconColor }}"
                     style="{{ $iconBg }}">
                    <span class="material-symbols-outlined text-[20px]"
                          style="font-variation-settings:'FILL' 1">{{ $icon }}</span>
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <p class="text-[14px] font-bold text-on-surface">{{ $perm->jenis_label }}</p>
                    <p class="text-[12px] text-on-surface-variant mt-0.5">
                        {{ $perm->tanggal->translatedFormat('l, d M Y') }}
                    </p>
                </div>

                {{-- Status badge --}}
                <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full {{ $badgeBg }} flex-shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                    {{ $perm->status_label }}
                </span>
            </div>

            {{-- Alasan --}}
            <div class="px-md pb-md">
                <div class="bg-surface-container/50 rounded-xl px-md py-sm">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-outline mb-1">Alasan</p>
                    <p class="text-[13px] text-on-surface leading-relaxed line-clamp-2">
                        {{ $perm->alasan }}
                    </p>
                </div>

                {{-- Catatan approver (jika ada) --}}
                @if($perm->catatan_approver)
                <div class="mt-sm flex items-start gap-xs">
                    <span class="material-symbols-outlined text-[14px] text-outline mt-0.5">comment</span>
                    <p class="text-[12px] text-on-surface-variant italic leading-relaxed">
                        {{ $perm->catatan_approver }}
                    </p>
                </div>
                @endif
            </div>
        </div>

        @empty

        {{-- Empty state --}}
        <div style="grid-column: 1 / -1;" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm flex flex-col items-center justify-center py-16 text-center">
            <div class="w-16 h-16 rounded-2xl bg-surface-container flex items-center justify-center mb-md">
                <span class="material-symbols-outlined text-[32px] text-outline">event_busy</span>
            </div>
            <p class="text-[15px] font-bold text-on-surface mb-1">Belum Ada Riwayat</p>
            <p class="text-[13px] text-on-surface-variant mb-lg max-w-[200px]">
                Kamu belum pernah mengajukan izin sebelumnya.
            </p>
            <a href="{{ route('permissions.create') }}"
               class="inline-flex items-center gap-xs bg-primary text-white font-bold text-[13px]
                      px-lg py-sm rounded-2xl active:scale-95 transition-transform shadow-md"
               style="box-shadow: 0 4px 16px rgba(0,108,73,0.30)">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Ajukan Izin Sekarang
            </a>
        </div>

        @endforelse

        {{-- Pagination --}}
        @if($permissions->hasPages())
        <div style="grid-column: 1 / -1;">
            {{ $permissions->links() }}
        </div>
        @endif

    </div>
</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="permission" role="siswa" />
@endsection
