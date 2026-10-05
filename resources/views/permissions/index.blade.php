@extends('layouts.app')
@section('title', 'Riwayat Izin')

@section('content')
<div class="max-w-lg mx-auto">

    {{-- ── Header ─────────────────────────────────────────────────── --}}
    <section class="relative bg-primary overflow-hidden px-md pt-lg pb-lg"
             style="border-radius: 0 0 32px 32px;">
        {{-- Ornamen --}}
        <div class="pointer-events-none absolute -top-10 -right-10 w-44 h-44 rounded-full"
             style="background: rgba(255,255,255,0.07)"></div>
        <div class="pointer-events-none absolute top-6 -right-2 w-24 h-24 rounded-full"
             style="background: rgba(255,255,255,0.05)"></div>

        <div class="relative z-10 flex items-center justify-between">
            <div>
                <p class="text-white/60 text-[11px] font-semibold uppercase tracking-widest mb-1">
                    Manajemen Izin
                </p>
                <h2 class="text-white font-extrabold"
                    style="font-size: 24px; line-height: 30px; letter-spacing: -0.02em;">
                    Riwayat Izin
                </h2>
                <p class="text-white/60 text-[13px] mt-1">Semua pengajuan izin Anda</p>
            </div>
            <a href="{{ route('permissions.create') }}"
               class="flex-shrink-0 flex items-center gap-xs rounded-2xl px-md py-sm font-bold text-[13px]
                      active:scale-95 transition-all duration-150"
               style="background: rgba(255,255,255,0.20); border: 1px solid rgba(255,255,255,0.25); color: white;">
                <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1">add</span>
                Buat Izin
            </a>
        </div>
    </section>

    {{-- ── List ─────────────────────────────────────────────────────── --}}
    <div class="px-md pt-lg pb-xl space-y-sm">

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

        <div class="bg-white rounded-2xl border border-outline-variant/50 shadow-sm overflow-hidden">
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
        <div class="flex flex-col items-center justify-center py-16 text-center">
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
        <div class="pt-sm">
            {{ $permissions->links() }}
        </div>
        @endif

    </div>
</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="permission" role="siswa" />
@endsection
