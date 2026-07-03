@extends('layouts.app')
@section('title', 'Ajukan Izin')

@section('content')
<div class="max-w-lg mx-auto" x-data="permissionForm()">

    {{-- ── Header ─────────────────────────────────────────────────── --}}
    <section class="relative bg-primary overflow-hidden px-md pt-lg pb-lg"
             style="border-radius: 0 0 32px 32px;">
        {{-- Ornamen --}}
        <div class="pointer-events-none absolute -top-10 -right-10 w-44 h-44 rounded-full"
             style="background: rgba(255,255,255,0.07)"></div>
        <div class="pointer-events-none absolute top-6 -right-2 w-24 h-24 rounded-full"
             style="background: rgba(255,255,255,0.05)"></div>

        <div class="relative z-10">
            <p class="text-white/60 text-[11px] font-semibold uppercase tracking-widest mb-1">
                Manajemen Izin
            </p>
            <h2 class="text-white font-extrabold"
                style="font-size: 24px; line-height: 30px; letter-spacing: -0.02em;">
                Pengajuan Izin
            </h2>
            <p class="text-white/60 text-[13px] mt-1">
                Lengkapi form di bawah untuk mengajukan izin
            </p>
        </div>
    </section>

    <div class="px-md pt-lg pb-xl space-y-lg">

        {{-- ── Flash success ────────────────────────────────────────── --}}
        @if(session('success'))
        <div class="flex items-start gap-sm bg-emerald-50 border border-emerald-200 rounded-2xl p-md">
            <span class="material-symbols-outlined text-emerald-600 text-[20px] flex-shrink-0 mt-0.5"
                  style="font-variation-settings:'FILL' 1">check_circle</span>
            <p class="text-[13px] text-emerald-800 font-semibold leading-relaxed">
                {{ session('success') }}
            </p>
        </div>
        @endif

        {{-- ── Form Card ────────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-outline-variant/50 shadow-sm overflow-hidden">

            {{-- Header card --}}
            <div class="flex items-center gap-sm px-md py-md border-b border-outline-variant/40">
                <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-[18px]"
                          style="font-variation-settings:'FILL' 1">edit_note</span>
                </div>
                <div>
                    <p class="text-[14px] font-bold text-on-surface">Form Pengajuan</p>
                    <p class="text-[11px] text-on-surface-variant">Isi semua kolom yang diperlukan</p>
                </div>
            </div>

            <form method="POST" action="{{ route('permissions.store') }}"
                  enctype="multipart/form-data"
                  class="p-md space-y-md">
                @csrf

                {{-- ── Jenis Izin ──────────────────────────────────── --}}
                <div>
                    <label for="jenis"
                           class="block text-[12px] font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Jenis Izin <span class="text-red-500">*</span>
                    </label>

                    {{-- Pilihan jenis sebagai button card --}}
                    <div class="grid grid-cols-3 gap-sm">
                        @php
                            $jenisOptions = [
                                'sakit'      => ['label' => 'Sakit',      'icon' => 'medical_services', 'color' => 'text-secondary', 'bg' => 'rgba(133,83,0,0.10)'],
                                'izin'       => ['label' => 'Izin',       'icon' => 'info',             'color' => 'text-tertiary',  'bg' => 'rgba(164,58,58,0.10)'],
                                'dispensasi' => ['label' => 'Dispensasi', 'icon' => 'event_available',  'color' => 'text-primary',   'bg' => 'rgba(0,108,73,0.10)'],
                            ];
                        @endphp

                        @foreach($jenisOptions as $val => $opt)
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis" value="{{ $val }}"
                                   x-model="selectedJenis"
                                   class="sr-only"
                                   {{ old('jenis') === $val ? 'checked' : '' }}>
                            <div class="flex flex-col items-center gap-1.5 p-sm rounded-2xl border-2 transition-all duration-150 text-center"
                                 :class="selectedJenis === '{{ $val }}'
                                     ? 'border-primary bg-primary/5'
                                     : 'border-outline-variant/50 bg-surface-container/30 hover:bg-surface-container'">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $opt['color'] }}"
                                     style="background: {{ $opt['bg'] }}">
                                    <span class="material-symbols-outlined text-[20px]"
                                          style="font-variation-settings:'FILL' 1">{{ $opt['icon'] }}</span>
                                </div>
                                <span class="text-[12px] font-bold text-on-surface">{{ $opt['label'] }}</span>
                                {{-- Checkmark saat dipilih --}}
                                <div x-show="selectedJenis === '{{ $val }}'"
                                     class="w-4 h-4 rounded-full bg-primary flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white text-[10px]"
                                          style="font-variation-settings:'FILL' 1; font-size:11px">check</span>
                                </div>
                                <div x-show="selectedJenis !== '{{ $val }}'"
                                     class="w-4 h-4 rounded-full border-2 border-outline-variant"></div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('jenis')
                    <p class="mt-1.5 text-[12px] text-red-500 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">error</span>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- ── Tanggal Izin ─────────────────────────────────── --}}
                <div>
                    <label for="tanggal"
                           class="block text-[12px] font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Tanggal Izin <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">
                            calendar_today
                        </span>
                        <input id="tanggal" type="date" name="tanggal"
                               value="{{ old('tanggal') }}"
                               class="w-full h-touch-target pl-10 pr-md rounded-2xl border border-outline-variant/60
                                      bg-surface-container/30 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20
                                      text-[14px] font-semibold text-on-surface transition-all duration-150 outline-none"
                               required>
                    </div>
                    @error('tanggal')
                    <p class="mt-1.5 text-[12px] text-red-500 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">error</span>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- ── Alasan ───────────────────────────────────────── --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="alasan"
                               class="block text-[12px] font-bold uppercase tracking-wider text-on-surface-variant">
                            Alasan <span class="text-red-500">*</span>
                        </label>
                        <span class="text-[11px] text-outline" x-text="alasanLen + '/500'">0/500</span>
                    </div>
                    <textarea id="alasan" name="alasan" rows="4"
                              placeholder="Jelaskan alasan izin secara jelas dan singkat..."
                              maxlength="500"
                              x-model="alasan"
                              @input="alasanLen = alasan.length"
                              class="w-full p-md rounded-2xl border border-outline-variant/60
                                     bg-surface-container/30 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20
                                     text-[14px] text-on-surface resize-none transition-all duration-150 outline-none leading-relaxed"
                              required>{{ old('alasan') }}</textarea>
                    @error('alasan')
                    <p class="mt-1.5 text-[12px] text-red-500 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">error</span>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- ── Lampiran ─────────────────────────────────────── --}}
                <div>
                    <label class="block text-[12px] font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Lampiran
                        <span class="text-outline font-semibold normal-case tracking-normal ml-1">(Opsional)</span>
                    </label>

                    <div class="relative border-2 border-dashed rounded-2xl transition-all duration-150 cursor-pointer overflow-hidden"
                         :class="fileName
                             ? 'border-primary bg-primary/5'
                             : 'border-outline-variant/60 bg-surface-container/30 hover:bg-surface-container hover:border-outline'">
                        <input type="file" name="lampiran"
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                               @change="fileName = $event.target.files[0]?.name">

                        <div class="flex flex-col items-center justify-center py-lg px-md text-center">
                            <div class="w-12 h-12 rounded-2xl mb-sm flex items-center justify-center"
                                 :class="fileName ? 'bg-primary/10' : 'bg-surface-container'"
                                 style="transition: all 0.15s">
                                <span class="material-symbols-outlined text-[24px]"
                                      :class="fileName ? 'text-primary' : 'text-outline'"
                                      style="font-variation-settings:'FILL' 1"
                                      x-text="fileName ? 'check_circle' : 'cloud_upload'">cloud_upload</span>
                            </div>
                            <p class="text-[13px] font-semibold text-center leading-snug"
                               :class="fileName ? 'text-primary' : 'text-on-surface-variant'"
                               x-text="fileName || 'Klik untuk upload surat atau bukti pendukung'">
                                Klik untuk upload surat atau bukti pendukung
                            </p>
                            <p class="text-[11px] text-outline mt-1">PDF, JPG, PNG — Maks. 5 MB</p>
                        </div>
                    </div>

                    @error('lampiran')
                    <p class="mt-1.5 text-[12px] text-red-500 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">error</span>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- ── Submit ───────────────────────────────────────── --}}
                <button type="submit"
                        class="w-full h-touch-target rounded-2xl font-extrabold text-[14px] text-white
                               flex items-center justify-center gap-sm
                               transition-all duration-200 active:scale-[0.98]
                               disabled:opacity-50 disabled:cursor-not-allowed"
                        style="background: #006c49; box-shadow: 0 6px 24px rgba(0,108,73,0.35);"
                        :disabled="submitting"
                        @click="submitting = true">
                    <span class="material-symbols-outlined text-[18px]"
                          x-show="!submitting"
                          style="font-variation-settings:'FILL' 1">send</span>
                    <span class="material-symbols-outlined text-[18px] animate-spin"
                          x-show="submitting">progress_activity</span>
                    <span x-text="submitting ? 'Mengirim Pengajuan...' : 'Kirim Pengajuan'">
                        Kirim Pengajuan
                    </span>
                </button>
            </form>
        </div>

        {{-- ── Riwayat Izin Terbaru ─────────────────────────────────── --}}
        @php
            $recentPerms = auth()->user()->permissions()->latest()->take(5)->get();
        @endphp

        @if($recentPerms->isNotEmpty())
        <div>
            <div class="flex items-center justify-between mb-md">
                <h3 class="text-[13px] font-bold text-on-surface">Pengajuan Terakhir</h3>
                <a href="{{ route('permissions.index') }}"
                   class="text-[12px] font-semibold text-primary flex items-center gap-0.5">
                    Lihat Semua
                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                </a>
            </div>

            <div class="space-y-sm">
                @foreach($recentPerms as $perm)
                @php
                    $iconBg    = match($perm->jenis) { 'sakit' => 'rgba(133,83,0,0.10)', 'dispensasi' => 'rgba(0,108,73,0.10)', default => 'rgba(164,58,58,0.10)' };
                    $iconColor = match($perm->jenis) { 'sakit' => 'text-secondary', 'dispensasi' => 'text-primary', default => 'text-tertiary' };
                    $icon      = match($perm->jenis) { 'sakit' => 'medical_services', 'dispensasi' => 'event_available', default => 'info' };
                    $badgeBg   = match($perm->status) { 'disetujui' => 'bg-emerald-100 text-emerald-700', 'ditolak' => 'bg-red-100 text-red-600', default => 'bg-amber-100 text-amber-700' };
                    $dotColor  = match($perm->status) { 'disetujui' => 'bg-emerald-500', 'ditolak' => 'bg-red-500', default => 'bg-amber-500' };
                @endphp

                <div class="bg-white rounded-2xl border border-outline-variant/50 shadow-sm px-md py-sm
                            flex items-center gap-md">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $iconColor }}"
                         style="background: {{ $iconBg }}">
                        <span class="material-symbols-outlined text-[18px]"
                              style="font-variation-settings:'FILL' 1">{{ $icon }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-bold text-on-surface">{{ $perm->jenis_label }}</p>
                        <p class="text-[11px] text-on-surface-variant mt-0.5">
                            {{ $perm->tanggal->translatedFormat('d M Y') }}
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-full {{ $badgeBg }} flex-shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                        {{ $perm->status_label }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="permission" role="siswa" />
@endsection

@push('scripts')
<script>
function permissionForm() {
    return {
        fileName:    '',
        submitting:  false,
        selectedJenis: '{{ old('jenis', '') }}',
        alasan:      '{{ old('alasan', '') }}',
        alasanLen:   {{ strlen(old('alasan', '')) }},
    };
}
</script>
@endpush
