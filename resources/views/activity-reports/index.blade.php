@extends('layouts.app')
@section('title', 'Laporan Kegiatan Saya')

@section('content')
<div class="px-md max-w-lg mx-auto pt-lg pb-4">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-lg">
        <div>
            <h2 class="text-headline-lg-mobile font-bold text-on-surface">Laporan Kegiatan</h2>
            <p class="text-body-sm text-on-surface-variant">Dokumentasi kegiatan magang</p>
        </div>
        <a href="{{ route('activity-reports.create') }}"
           class="flex items-center gap-xs bg-primary text-on-primary px-md py-sm rounded-xl
                  text-body-sm font-semibold active:scale-95 transition-transform shadow-sm">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Upload
        </a>
    </div>

    @if(session('success'))
        <div class="mb-lg p-md bg-primary-container text-on-primary-container rounded-xl
                    flex items-center gap-sm text-body-sm font-semibold">
            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-md">
        @forelse($reports as $report)
        @php
            $firstImg = $report->files->firstWhere('mime_type', fn ($m) => str_starts_with($m, 'image/'));
            $firstVid = $report->files->firstWhere('mime_type', fn ($m) => str_starts_with($m, 'video/'));
            $preview  = $firstImg ?? $firstVid;
        @endphp
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            {{-- Thumbnail --}}
            @if($preview)
                <a href="{{ route('activity-reports.show', $report) }}" class="block h-40 bg-surface-container relative overflow-hidden">
                    @if(str_starts_with($preview->mime_type, 'image/'))
                        <img src="{{ $preview->url }}" alt="{{ $report->judul }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-inverse-surface/10">
                            <span class="material-symbols-outlined text-6xl text-outline"
                                  style="font-variation-settings:'FILL' 1">play_circle</span>
                        </div>
                    @endif
                    {{-- File count badge --}}
                    <div class="absolute bottom-sm right-sm bg-black/60 text-white text-label-md
                                px-sm py-0.5 rounded-full flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[14px]">perm_media</span>
                        {{ $report->files->count() }} file
                    </div>
                </a>
            @endif

            <div class="p-md">
                <div class="flex items-start justify-between gap-sm">
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('activity-reports.show', $report) }}"
                           class="text-body-lg font-semibold text-on-surface hover:text-primary transition-colors line-clamp-2">
                            {{ $report->judul }}
                        </a>
                        <p class="text-label-md text-outline mt-xs flex items-center gap-xs">
                            <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                            {{ $report->tanggal->translatedFormat('d F Y') }}
                        </p>
                    </div>

                    {{-- Delete --}}
                    <form method="POST" action="{{ route('activity-reports.destroy', $report) }}"
                          onsubmit="return confirm('Hapus laporan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="material-symbols-outlined text-outline hover:text-error
                                       transition-colors p-1 shrink-0">
                            delete_outline
                        </button>
                    </form>
                </div>

                @if($report->deskripsi)
                    <p class="text-body-sm text-on-surface-variant mt-sm line-clamp-2">
                        {{ $report->deskripsi }}
                    </p>
                @endif

                {{-- File type badges --}}
                <div class="flex gap-xs mt-sm flex-wrap">
                    @if($report->files->contains(fn($f) => str_starts_with($f->mime_type, 'image/')))
                        <span class="text-label-md px-sm py-0.5 rounded-full bg-primary-container/20 text-primary flex items-center gap-xs">
                            <span class="material-symbols-outlined text-[12px]">image</span>
                            Foto
                        </span>
                    @endif
                    @if($report->files->contains(fn($f) => str_starts_with($f->mime_type, 'video/')))
                        <span class="text-label-md px-sm py-0.5 rounded-full bg-secondary-container/30 text-secondary flex items-center gap-xs">
                            <span class="material-symbols-outlined text-[12px]">videocam</span>
                            Video
                        </span>
                    @endif
                    @if($report->files->contains(fn($f) => !str_starts_with($f->mime_type, 'image/') && !str_starts_with($f->mime_type, 'video/')))
                        <span class="text-label-md px-sm py-0.5 rounded-full bg-surface-container text-outline flex items-center gap-xs">
                            <span class="material-symbols-outlined text-[12px]">attach_file</span>
                            Dokumen
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-xl text-center">
            <span class="material-symbols-outlined text-5xl text-outline block mb-sm"
                  style="font-variation-settings:'FILL' 1">add_photo_alternate</span>
            <p class="text-body-lg text-on-surface-variant mb-md">Belum ada laporan kegiatan</p>
            <a href="{{ route('activity-reports.create') }}"
               class="inline-flex items-center gap-sm bg-primary text-on-primary px-lg py-sm
                      rounded-xl text-body-sm font-semibold">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Upload Laporan Pertama
            </a>
        </div>
        @endforelse
    </div>

    <div class="mt-lg">{{ $reports->links() }}</div>

</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="activity" role="siswa" />
@endsection
