@extends('layouts.app')
@section('title', 'Riwayat Izin')

@section('content')
<div class="px-md max-w-lg mx-auto pt-lg">
    <div class="flex items-center justify-between mb-lg">
        <div>
            <h2 class="text-headline-lg-mobile font-bold text-on-surface">Riwayat Izin</h2>
            <p class="text-body-sm text-on-surface-variant">Semua pengajuan izin Anda</p>
        </div>
        <a href="{{ route('permissions.create') }}"
           class="flex items-center gap-xs bg-primary text-on-primary px-md py-sm rounded-xl text-body-sm font-semibold
                  active:scale-95 transition-transform shadow-sm">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Buat Izin
        </a>
    </div>

    <div class="space-y-sm">
        @forelse($permissions as $perm)
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-md flex flex-col gap-sm shadow-sm">
            <div class="flex justify-between items-start">
                <div class="flex items-center gap-sm">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0
                        {{ $perm->jenis === 'sakit' ? 'bg-secondary-container text-on-secondary-container' :
                           ($perm->jenis === 'dispensasi' ? 'bg-primary-container text-on-primary-container' : 'bg-tertiary-container text-on-tertiary-container') }}">
                        <span class="material-symbols-outlined text-[18px]">
                            {{ $perm->jenis === 'sakit' ? 'medical_services' : ($perm->jenis === 'dispensasi' ? 'event_available' : 'info') }}
                        </span>
                    </div>
                    <div>
                        <p class="text-body-lg font-semibold text-on-surface">{{ $perm->jenis_label }}</p>
                        <p class="text-body-sm text-outline">{{ $perm->tanggal->translatedFormat('d M Y') }}</p>
                    </div>
                </div>
                @php
                    $badgeClass = match($perm->status) {
                        'disetujui' => 'bg-primary-container text-on-primary-container',
                        'ditolak'   => 'bg-error-container text-on-error-container',
                        default     => 'bg-secondary-container text-on-secondary-container',
                    };
                @endphp
                <span class="px-sm py-0.5 rounded-full {{ $badgeClass }} text-status-badge font-bold whitespace-nowrap">
                    {{ $perm->status_label }}
                </span>
            </div>
            <p class="text-body-sm text-on-surface-variant line-clamp-2">"{{ $perm->alasan }}"</p>
            @if($perm->catatan_approver)
                <p class="text-label-md text-outline italic">Catatan: {{ $perm->catatan_approver }}</p>
            @endif
        </div>
        @empty
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-xl text-center">
            <span class="material-symbols-outlined text-4xl text-outline mb-sm block">event_busy</span>
            <p class="text-body-lg text-on-surface-variant">Belum ada riwayat izin</p>
            <a href="{{ route('permissions.create') }}" class="inline-block mt-md text-primary text-body-sm hover:underline">
                Ajukan izin sekarang
            </a>
        </div>
        @endforelse
    </div>

    <div class="mt-lg">
        {{ $permissions->links() }}
    </div>
</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="permission" role="siswa" />
@endsection
