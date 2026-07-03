@extends('layouts.app')
@section('title', $activityReport->judul)

@section('content')
<div class="max-w-lg mx-auto" x-data="{ lightbox: null }">

    {{-- ── Hero Header ─────────────────────────────────────────────── --}}
    <section class="relative bg-primary overflow-hidden px-md pt-lg pb-lg"
             style="border-radius: 0 0 32px 32px;">
        <div class="pointer-events-none absolute -top-10 -right-10 w-44 h-44 rounded-full"
             style="background: rgba(255,255,255,0.07)"></div>
        <div class="pointer-events-none absolute top-6 -right-2 w-24 h-24 rounded-full"
             style="background: rgba(255,255,255,0.05)"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-sm mb-2">
                <a href="{{ auth()->user()->isGuru() ? route('activity-reports.guru') : route('activity-reports.index') }}"
                   class="w-8 h-8 rounded-xl flex items-center justify-center transition-colors flex-shrink-0"
                   style="background: rgba(255,255,255,0.15)">
                    <span class="material-symbols-outlined text-white text-[18px]">arrow_back</span>
                </a>
                <p class="text-white/60 text-[11px] font-semibold uppercase tracking-widest">Detail Laporan</p>
            </div>
            <h2 class="text-white font-extrabold line-clamp-2"
                style="font-size: 20px; line-height: 26px; letter-spacing: -0.01em;">
                {{ $activityReport->judul }}
            </h2>
        </div>
    </section>

    <div class="px-md pt-lg pb-xl space-y-lg">

        {{-- ── Info Card ─────────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-outline-variant/50 shadow-sm overflow-hidden">

            {{-- Profil siswa (untuk guru/admin) --}}
            @if(auth()->user()->isGuru() || auth()->user()->isAdmin())
            <div class="flex items-center gap-md px-md py-md border-b border-outline-variant/40">
                <img src="{{ $activityReport->user->photo_url }}"
                     class="w-10 h-10 rounded-full object-cover flex-shrink-0"
                     alt="{{ $activityReport->user->name }}">
                <div>
                    <p class="text-[14px] font-bold text-on-surface">{{ $activityReport->user->name }}</p>
                    <p class="text-[12px] text-on-surface-variant">{{ $activityReport->user->jurusan ?? '—' }}</p>
                </div>
            </div>
            @endif

            {{-- Detail info --}}
            <div class="grid grid-cols-2 gap-sm p-md">
                <div class="bg-surface-container/50 rounded-xl p-sm">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-1">Tanggal</p>
                    <p class="text-[13px] font-semibold text-on-surface leading-tight">
                        {{ $activityReport->tanggal->translatedFormat('l, d F Y') }}
                    </p>
                </div>
                <div class="bg-surface-container/50 rounded-xl p-sm">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-1">Jumlah File</p>
                    <p class="text-[13px] font-semibold text-on-surface">
                        {{ $activityReport->files->count() }} file
                    </p>
                </div>
            </div>

            @if($activityReport->deskripsi)
            <div class="px-md pb-md border-t border-outline-variant/30 pt-sm">
                <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-1.5">Deskripsi Kegiatan</p>
                <p class="text-[13px] text-on-surface leading-relaxed">{{ $activityReport->deskripsi }}</p>
            </div>
            @endif
        </div>

        {{-- ── Foto ──────────────────────────────────────────────────── --}}
        @php
            $images    = $activityReport->files->filter(fn ($f) => $f->type === 'image');
            $videos    = $activityReport->files->filter(fn ($f) => $f->type === 'video');
            $documents = $activityReport->files->filter(fn ($f) => $f->type === 'document');
        @endphp

        @if($images->count())
        <div>
            <div class="flex items-center gap-sm mb-md">
                <div class="w-7 h-7 rounded-xl bg-primary/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-[15px]"
                          style="font-variation-settings:'FILL' 1">image</span>
                </div>
                <h3 class="text-[13px] font-bold text-on-surface">
                    Foto <span class="text-on-surface-variant font-normal">({{ $images->count() }})</span>
                </h3>
            </div>
            <div class="grid grid-cols-3 gap-sm">
                @foreach($images as $img)
                <button @click="lightbox = '{{ $img->url }}'"
                        class="aspect-square rounded-2xl overflow-hidden border border-outline-variant/40
                               active:scale-95 transition-all duration-150 shadow-sm">
                    <img src="{{ $img->url }}" alt="{{ $img->original_name }}"
                         class="w-full h-full object-cover">
                </button>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── Video ─────────────────────────────────────────────────── --}}
        @if($videos->count())
        <div>
            <div class="flex items-center gap-sm mb-md">
                <div class="w-7 h-7 rounded-xl bg-secondary/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-secondary text-[15px]"
                          style="font-variation-settings:'FILL' 1">videocam</span>
                </div>
                <h3 class="text-[13px] font-bold text-on-surface">
                    Video <span class="text-on-surface-variant font-normal">({{ $videos->count() }})</span>
                </h3>
            </div>
            <div class="space-y-sm">
                @foreach($videos as $vid)
                <div class="bg-white rounded-2xl border border-outline-variant/50 shadow-sm overflow-hidden">
                    <video controls class="w-full max-h-60 bg-black" preload="metadata">
                        <source src="{{ $vid->url }}" type="{{ $vid->mime_type }}">
                        Browser Anda tidak mendukung pemutaran video.
                    </video>
                    <div class="flex items-center gap-sm px-md py-sm">
                        <span class="material-symbols-outlined text-outline text-[15px]">videocam</span>
                        <p class="text-[12px] text-on-surface-variant truncate flex-1">{{ $vid->original_name }}</p>
                        <span class="text-[11px] text-outline flex-shrink-0">{{ $vid->formatted_size }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── Dokumen ────────────────────────────────────────────────── --}}
        @if($documents->count())
        <div>
            <div class="flex items-center gap-sm mb-md">
                <div class="w-7 h-7 rounded-xl bg-slate-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-slate-500 text-[15px]"
                          style="font-variation-settings:'FILL' 1">attach_file</span>
                </div>
                <h3 class="text-[13px] font-bold text-on-surface">
                    Dokumen <span class="text-on-surface-variant font-normal">({{ $documents->count() }})</span>
                </h3>
            </div>
            <div class="space-y-sm">
                @foreach($documents as $doc)
                @php
                    $icon = match(true) {
                        str_contains($doc->mime_type, 'pdf')          => 'picture_as_pdf',
                        str_contains($doc->mime_type, 'word') ||
                        str_contains($doc->mime_type, 'document')     => 'description',
                        str_contains($doc->mime_type, 'sheet') ||
                        str_contains($doc->mime_type, 'excel')        => 'table_chart',
                        str_contains($doc->mime_type, 'presentation')||
                        str_contains($doc->mime_type, 'powerpoint')   => 'slideshow',
                        default                                        => 'attach_file',
                    };
                @endphp
                <a href="{{ $doc->url }}" target="_blank"
                   class="flex items-center gap-md bg-white rounded-2xl border border-outline-variant/50
                          shadow-sm px-md py-sm active:scale-[0.98] transition-all duration-150 group">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-primary text-[20px]"
                              style="font-variation-settings:'FILL' 1">{{ $icon }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-semibold text-on-surface truncate">{{ $doc->original_name }}</p>
                        <p class="text-[11px] text-outline">{{ $doc->formatted_size }}</p>
                    </div>
                    <span class="material-symbols-outlined text-[18px] text-outline/60
                                 group-active:translate-x-0.5 transition-transform">open_in_new</span>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- ── Lightbox foto ─────────────────────────────────────────────── --}}
    <div x-show="lightbox"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-md"
         @click="lightbox = null"
         @keydown.escape.window="lightbox = null"
         style="display:none">
        <img :src="lightbox" class="max-w-full max-h-full rounded-2xl object-contain" @click.stop>
        <button @click="lightbox = null"
                class="absolute top-md right-md w-10 h-10 rounded-full flex items-center justify-center
                       text-white transition-colors"
                style="background: rgba(255,255,255,0.15)">
            <span class="material-symbols-outlined text-[22px]">close</span>
        </button>
    </div>

</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav :active="'activity'" :role="auth()->user()->role" />
@endsection
