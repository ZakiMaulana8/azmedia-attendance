@extends('layouts.app')
@section('title', $activityReport->judul)

@section('content')
<div class="px-md max-w-lg mx-auto pt-lg pb-4" x-data="{ lightbox: null }">

    {{-- Back --}}
    <div class="flex items-center gap-sm mb-lg">
        <a href="{{ auth()->user()->isGuru() ? route('activity-reports.guru') : route('activity-reports.index') }}"
           class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors">
            arrow_back
        </a>
        <h2 class="text-headline-lg-mobile font-bold text-on-surface line-clamp-2">
            {{ $activityReport->judul }}
        </h2>
    </div>

    {{-- Meta info --}}
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm mb-lg space-y-sm">
        {{-- Siswa --}}
        @if(auth()->user()->isGuru() || auth()->user()->isAdmin())
        <div class="flex items-center gap-sm pb-sm border-b border-outline-variant">
            <img src="{{ $activityReport->user->photo_url }}"
                 class="w-10 h-10 rounded-full object-cover"
                 alt="{{ $activityReport->user->name }}">
            <div>
                <p class="text-body-sm font-semibold text-on-surface">{{ $activityReport->user->name }}</p>
                <p class="text-label-md text-outline">{{ $activityReport->user->jurusan ?? '—' }}</p>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-2 gap-sm text-body-sm">
            <div>
                <p class="text-label-md text-outline uppercase mb-xs">Tanggal</p>
                <p class="font-semibold text-on-surface">
                    {{ $activityReport->tanggal->translatedFormat('l, d F Y') }}
                </p>
            </div>
            <div>
                <p class="text-label-md text-outline uppercase mb-xs">Jumlah File</p>
                <p class="font-semibold text-on-surface">{{ $activityReport->files->count() }} file</p>
            </div>
        </div>

        @if($activityReport->deskripsi)
        <div class="pt-sm border-t border-outline-variant">
            <p class="text-label-md text-outline uppercase mb-xs">Deskripsi Kegiatan</p>
            <p class="text-body-sm text-on-surface leading-relaxed">{{ $activityReport->deskripsi }}</p>
        </div>
        @endif
    </div>

    {{-- File list --}}
    @php
        $images    = $activityReport->files->filter(fn ($f) => $f->type === 'image');
        $videos    = $activityReport->files->filter(fn ($f) => $f->type === 'video');
        $documents = $activityReport->files->filter(fn ($f) => $f->type === 'document');
    @endphp

    {{-- Foto --}}
    @if($images->count())
    <section class="mb-lg">
        <p class="text-label-md text-on-surface-variant uppercase tracking-wider mb-sm flex items-center gap-sm">
            <span class="material-symbols-outlined text-primary text-[18px]">image</span>
            Foto ({{ $images->count() }})
        </p>
        <div class="grid grid-cols-3 gap-sm">
            @foreach($images as $img)
            <button @click="lightbox = '{{ $img->url }}'"
                    class="aspect-square rounded-xl overflow-hidden border border-outline-variant
                           hover:opacity-90 active:scale-95 transition-all">
                <img src="{{ $img->url }}" alt="{{ $img->original_name }}"
                     class="w-full h-full object-cover">
            </button>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Video --}}
    @if($videos->count())
    <section class="mb-lg">
        <p class="text-label-md text-on-surface-variant uppercase tracking-wider mb-sm flex items-center gap-sm">
            <span class="material-symbols-outlined text-secondary text-[18px]">videocam</span>
            Video ({{ $videos->count() }})
        </p>
        <div class="space-y-sm">
            @foreach($videos as $vid)
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
                <video controls class="w-full max-h-64 bg-black"
                       preload="metadata">
                    <source src="{{ $vid->url }}" type="{{ $vid->mime_type }}">
                    Browser Anda tidak mendukung pemutaran video.
                </video>
                <div class="p-sm flex items-center gap-sm">
                    <span class="material-symbols-outlined text-outline text-[16px]">videocam</span>
                    <p class="text-label-md text-on-surface-variant truncate">{{ $vid->original_name }}</p>
                    <span class="ml-auto text-label-md text-outline">{{ $vid->formatted_size }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Dokumen --}}
    @if($documents->count())
    <section class="mb-lg">
        <p class="text-label-md text-on-surface-variant uppercase tracking-wider mb-sm flex items-center gap-sm">
            <span class="material-symbols-outlined text-outline text-[18px]">attach_file</span>
            Dokumen ({{ $documents->count() }})
        </p>
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
               class="flex items-center gap-md bg-surface-container-low rounded-xl px-md py-sm
                      hover:bg-surface-container transition-colors border border-outline-variant">
                <span class="material-symbols-outlined text-primary text-[28px] shrink-0"
                      style="font-variation-settings:'FILL' 1">{{ $icon }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-body-sm font-semibold text-on-surface truncate">{{ $doc->original_name }}</p>
                    <p class="text-label-md text-outline">{{ $doc->formatted_size }}</p>
                </div>
                <span class="material-symbols-outlined text-outline shrink-0">open_in_new</span>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ── Lightbox foto ─────────────────────────────────────────────────────── --}}
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
        <img :src="lightbox"
             class="max-w-full max-h-full rounded-lg object-contain"
             @click.stop>
        <button @click="lightbox = null"
                class="absolute top-md right-md material-symbols-outlined text-white text-3xl
                       hover:text-outline transition-colors">
            close
        </button>
    </div>

</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav :active="auth()->user()->isGuru() ? 'activity' : 'activity'"
                  :role="auth()->user()->role" />
@endsection
