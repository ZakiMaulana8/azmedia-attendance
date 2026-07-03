@extends('layouts.app')
@section('title', 'Laporan Kegiatan')

@section('content')
<div class="max-w-lg mx-auto">

    {{-- ── Hero Header ─────────────────────────────────────────────── --}}
    <section class="relative bg-primary overflow-hidden px-md pt-lg pb-lg"
             style="border-radius: 0 0 32px 32px;">
        <div class="pointer-events-none absolute -top-10 -right-10 w-44 h-44 rounded-full"
             style="background: rgba(255,255,255,0.07)"></div>
        <div class="pointer-events-none absolute top-6 -right-2 w-24 h-24 rounded-full"
             style="background: rgba(255,255,255,0.05)"></div>

        <div class="relative z-10 flex items-center justify-between">
            <div>
                <p class="text-white/60 text-[11px] font-semibold uppercase tracking-widest mb-1">
                    Dokumentasi Magang
                </p>
                <h2 class="text-white font-extrabold"
                    style="font-size: 24px; line-height: 30px; letter-spacing: -0.02em;">
                    Laporan Kegiatan
                </h2>
                <p class="text-white/60 text-[13px] mt-1">Dokumentasi kegiatan magang</p>
            </div>
            <a href="{{ route('activity-reports.create') }}"
               class="flex-shrink-0 flex items-center gap-xs rounded-2xl px-md py-sm font-bold text-[13px]
                      active:scale-95 transition-all duration-150 text-white"
               style="background: rgba(255,255,255,0.20); border: 1px solid rgba(255,255,255,0.25);">
                <span class="material-symbols-outlined text-[18px]"
                      style="font-variation-settings:'FILL' 1">add_photo_alternate</span>
                Upload
            </a>
        </div>
    </section>

    <div class="px-md pt-lg pb-xl">

        {{-- Flash success --}}
        @if(session('success'))
        <div class="flex items-center gap-sm bg-emerald-50 border border-emerald-200 rounded-2xl px-md py-sm mb-lg">
            <span class="material-symbols-outlined text-emerald-600 text-[18px] flex-shrink-0"
                  style="font-variation-settings:'FILL' 1">check_circle</span>
            <p class="text-[13px] text-emerald-800 font-semibold">{{ session('success') }}</p>
        </div>
        @endif

        {{-- List laporan --}}
        <div class="space-y-md">
            @forelse($reports as $report)
            @php
                $firstImg = $report->files->firstWhere('mime_type', fn ($m) => str_starts_with($m, 'image/'));
                $firstVid = $report->files->firstWhere('mime_type', fn ($m) => str_starts_with($m, 'video/'));
                $preview  = $firstImg ?? $firstVid;
                $hasImg   = $report->files->contains(fn($f) => str_starts_with($f->mime_type, 'image/'));
                $hasVid   = $report->files->contains(fn($f) => str_starts_with($f->mime_type, 'video/'));
                $hasDoc   = $report->files->contains(fn($f) => !str_starts_with($f->mime_type, 'image/') && !str_starts_with($f->mime_type, 'video/'));
            @endphp

            <div class="bg-white rounded-2xl border border-outline-variant/50 shadow-sm overflow-hidden">

                {{-- Thumbnail --}}
                @if($preview)
                <a href="{{ route('activity-reports.show', $report) }}"
                   class="block relative overflow-hidden" style="height: 160px;">
                    @if(str_starts_with($preview->mime_type, 'image/'))
                        <img src="{{ $preview->url }}" alt="{{ $report->judul }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-surface-container">
                            <div class="w-14 h-14 rounded-2xl bg-white/80 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[32px] text-primary"
                                      style="font-variation-settings:'FILL' 1">play_circle</span>
                            </div>
                        </div>
                    @endif
                    {{-- Overlay gradient --}}
                    <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(0,0,0,0.3) 0%, transparent 60%)"></div>
                    {{-- Badge jumlah file --}}
                    <div class="absolute bottom-sm right-sm flex items-center gap-1 rounded-full px-sm py-0.5
                                text-white text-[11px] font-bold"
                         style="background: rgba(0,0,0,0.55)">
                        <span class="material-symbols-outlined text-[12px]">perm_media</span>
                        {{ $report->files->count() }} file
                    </div>
                </a>
                @endif

                {{-- Konten card --}}
                <div class="p-md">
                    <div class="flex items-start justify-between gap-sm">
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('activity-reports.show', $report) }}"
                               class="text-[15px] font-bold text-on-surface line-clamp-2 leading-snug">
                                {{ $report->judul }}
                            </a>
                            <div class="flex items-center gap-xs mt-1.5">
                                <span class="material-symbols-outlined text-[13px] text-outline">calendar_today</span>
                                <p class="text-[12px] text-on-surface-variant">
                                    {{ $report->tanggal->translatedFormat('l, d F Y') }}
                                </p>
                            </div>
                        </div>

                        {{-- Tombol hapus --}}
                        <form method="POST" action="{{ route('activity-reports.destroy', $report) }}"
                              onsubmit="return confirm('Hapus laporan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-8 h-8 rounded-xl bg-surface-container flex items-center justify-center
                                           text-outline active:bg-red-50 active:text-red-500 transition-colors flex-shrink-0">
                                <span class="material-symbols-outlined text-[18px]">delete_outline</span>
                            </button>
                        </form>
                    </div>

                    @if($report->deskripsi)
                    <p class="text-[12px] text-on-surface-variant mt-sm line-clamp-2 leading-relaxed">
                        {{ $report->deskripsi }}
                    </p>
                    @endif

                    {{-- Badge tipe file --}}
                    <div class="flex gap-xs mt-sm flex-wrap">
                        @if($hasImg)
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5
                                     rounded-full bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-[11px]">image</span>Foto
                        </span>
                        @endif
                        @if($hasVid)
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5
                                     rounded-full bg-secondary/10 text-secondary">
                            <span class="material-symbols-outlined text-[11px]">videocam</span>Video
                        </span>
                        @endif
                        @if($hasDoc)
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5
                                     rounded-full bg-slate-100 text-slate-600">
                            <span class="material-symbols-outlined text-[11px]">attach_file</span>Dokumen
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            @empty

            {{-- Empty state --}}
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-16 h-16 rounded-2xl bg-surface-container flex items-center justify-center mb-md">
                    <span class="material-symbols-outlined text-[32px] text-outline"
                          style="font-variation-settings:'FILL' 1">add_photo_alternate</span>
                </div>
                <p class="text-[15px] font-bold text-on-surface mb-1">Belum Ada Laporan</p>
                <p class="text-[13px] text-on-surface-variant mb-lg max-w-[200px]">
                    Dokumentasikan kegiatan magang kamu dengan foto atau video.
                </p>
                <a href="{{ route('activity-reports.create') }}"
                   class="inline-flex items-center gap-xs text-white font-bold text-[13px]
                          px-lg py-sm rounded-2xl active:scale-95 transition-transform"
                   style="background: #006c49; box-shadow: 0 4px 16px rgba(0,108,73,0.30)">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Upload Laporan Pertama
                </a>
            </div>

            @endforelse
        </div>

        {{-- Pagination --}}
        @if($reports->hasPages())
        <div class="mt-lg">{{ $reports->links() }}</div>
        @endif

    </div>
</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="activity" role="siswa" />
@endsection
