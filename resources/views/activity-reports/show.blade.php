@extends(auth()->user()?->isAdmin() ? 'layouts.admin' : 'layouts.app')
@section('title', $activityReport->judul)

@section('content')
<div class="max-w-6xl mx-auto p-4 sm:p-6 space-y-6"
     style="max-width: 760px;"
     x-data="{ lightbox: null }">

    {{-- ── Hero Header ─────────────────────────────────────────────── --}}
    <section class="relative overflow-hidden shadow-lg"
             style="background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 55%, #3B82F6 100%); border-radius: 24px; padding: 28px 24px;">
        <div class="pointer-events-none absolute inset-0 overflow-hidden" style="border-radius: 24px;">
            <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full" style="background:rgba(255,255,255,0.08); filter: blur(20px);"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full" style="background:rgba(255,255,255,0.06); filter: blur(16px);"></div>
        </div>

        <div class="relative z-10 flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-white/80 text-[12px] font-bold uppercase tracking-wider mb-1">Dokumentasi Magang</p>
                <h1 class="text-white font-extrabold text-[22px] sm:text-[26px] leading-tight tracking-tight line-clamp-2">
                    {{ $activityReport->judul }}
                </h1>
                <p class="text-white/80 text-[13px] font-medium mt-1">
                    {{ $activityReport->tanggal->translatedFormat('l, d F Y') }}
                </p>
            </div>
            <a href="{{ auth()->user()->isKaryawan() ? route('karyawan.activity-reports.index') : ((auth()->user()->isGuru() || auth()->user()->isAdmin()) ? route('activity-reports.guru') : route('activity-reports.index')) }}"
               class="flex-shrink-0 flex items-center gap-1.5 rounded-2xl px-4 py-2.5 font-bold text-[13px]
                      active:scale-95 transition-all duration-150 text-white hover:bg-white/30"
               style="background: rgba(255,255,255,0.20); border: 1px solid rgba(255,255,255,0.38); backdrop-filter: blur(10px);">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali
            </a>
        </div>
    </section>

    <div class="space-y-6">

        {{-- ── Info Card ─────────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">

            {{-- Profil siswa (untuk guru/admin) --}}
            @if(auth()->user()->isGuru() || auth()->user()->isAdmin())
            <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-200/80 bg-slate-50">
                <img src="{{ $activityReport->user->photo_url }}"
                     class="w-10 h-10 rounded-full object-cover flex-shrink-0"
                     alt="{{ $activityReport->user->name }}">
                <div>
                    <p class="text-[14px] font-bold text-slate-800">{{ $activityReport->user->name }}</p>
                    <p class="text-[12px] text-slate-500">{{ $activityReport->user->jurusan ?? '—' }}</p>
                </div>
            </div>
            @endif

            {{-- Detail info --}}
            <div class="grid grid-cols-2 gap-3 p-6">
                <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3.5">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Tanggal</p>
                    <p class="text-[13px] font-semibold text-slate-800 leading-tight">
                        {{ $activityReport->tanggal->translatedFormat('l, d F Y') }}
                    </p>
                </div>
                <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3.5">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Jumlah File</p>
                    <p class="text-[13px] font-semibold text-slate-800">
                        {{ $activityReport->files->count() }} file
                    </p>
                </div>
            </div>

            @if($activityReport->deskripsi)
            <div class="px-6 pb-6 border-t border-slate-100 pt-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Deskripsi Kegiatan</p>
                <p class="text-[13px] text-slate-800 leading-relaxed">{{ $activityReport->deskripsi }}</p>
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
            <div class="flex items-center gap-2 mb-3">
                <div class="w-7 h-7 rounded-xl bg-blue-50 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#2563EB] text-[16px]"
                          style="font-variation-settings:'FILL' 1">image</span>
                </div>
                <h3 class="text-[13px] font-bold text-slate-800">
                    Foto <span class="text-slate-400 font-normal">({{ $images->count() }})</span>
                </h3>
            </div>
            <div class="grid grid-cols-3 gap-3">
                @foreach($images as $img)
                <button @click="lightbox = '{{ $img->url }}'"
                        class="aspect-square rounded-2xl overflow-hidden border border-slate-200
                               active:scale-95 transition-all duration-150 shadow-sm hover:shadow">
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
            <div class="flex items-center gap-2 mb-3">
                <div class="w-7 h-7 rounded-xl bg-indigo-50 flex items-center justify-center">
                    <span class="material-symbols-outlined text-indigo-600 text-[16px]"
                          style="font-variation-settings:'FILL' 1">videocam</span>
                </div>
                <h3 class="text-[13px] font-bold text-slate-800">
                    Video <span class="text-slate-400 font-normal">({{ $videos->count() }})</span>
                </h3>
            </div>
            <div class="space-y-3">
                @foreach($videos as $vid)
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <video controls class="w-full max-h-60 bg-black" preload="metadata">
                        <source src="{{ $vid->url }}" type="{{ $vid->mime_type }}">
                        Browser Anda tidak mendukung pemutaran video.
                    </video>
                    <div class="flex items-center gap-2 px-4 py-3 border-t border-slate-100">
                        <span class="material-symbols-outlined text-slate-400 text-[16px]">videocam</span>
                        <p class="text-[12px] text-slate-600 truncate flex-1 font-medium">{{ $vid->original_name }}</p>
                        <span class="text-[11px] text-slate-400 flex-shrink-0">{{ $vid->formatted_size }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── Dokumen ────────────────────────────────────────────────── --}}
        @if($documents->count())
        <div>
            <div class="flex items-center gap-2 mb-3">
                <div class="w-7 h-7 rounded-xl bg-slate-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-slate-500 text-[16px]"
                          style="font-variation-settings:'FILL' 1">attach_file</span>
                </div>
                <h3 class="text-[13px] font-bold text-slate-800">
                    Dokumen <span class="text-slate-400 font-normal">({{ $documents->count() }})</span>
                </h3>
            </div>
            <div class="space-y-3">
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
                   class="flex items-center gap-3 bg-white rounded-2xl border border-slate-200/80
                          shadow-sm px-4 py-3 active:scale-[0.98] transition-all duration-150 group hover:border-blue-300">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-[#2563EB] text-[20px]"
                              style="font-variation-settings:'FILL' 1">{{ $icon }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-semibold text-slate-800 truncate group-hover:text-[#2563EB] transition-colors">{{ $doc->original_name }}</p>
                        <p class="text-[11px] text-slate-400">{{ $doc->formatted_size }}</p>
                    </div>
                    <span class="material-symbols-outlined text-[18px] text-slate-400
                                 group-hover:translate-x-0.5 group-hover:text-[#2563EB] transition-all">open_in_new</span>
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

@if(!auth()->user()?->isAdmin())
@section('bottom-nav')
    <x-bottom-nav :active="'activity'" :role="auth()->user()->role" />
@endsection
@endif
