@extends('layouts.app')
@section('title', 'Laporan Kegiatan Siswa')

@section('content')
<div class="px-md max-w-screen-xl mx-auto pt-lg pb-4">

    {{-- Header --}}
    <section class="mb-lg">
        <h2 class="text-headline-lg-mobile font-bold text-on-surface">Laporan Kegiatan Siswa</h2>
        <p class="text-body-sm text-on-surface-variant">Dokumentasi kegiatan magang seluruh siswa</p>
    </section>

    {{-- Filter bar --}}
    <form method="GET" action="{{ route('activity-reports.guru') }}"
          class="flex gap-sm mb-lg flex-wrap">
        {{-- Filter siswa --}}
        <div class="flex-1 min-w-40 relative">
            <select name="siswa"
                    class="w-full h-touch-target px-md rounded-xl border border-outline-variant
                           bg-surface-container-lowest focus:border-primary appearance-none text-body-sm"
                    onchange="this.form.submit()">
                <option value="">Semua Siswa</option>
                @foreach($siswaList as $s)
                    <option value="{{ $s->id }}" @selected($siswaId == $s->id)>
                        {{ $s->name }}@if($s->jurusan) ({{ $s->jurusan }})@endif
                    </option>
                @endforeach
            </select>
            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-outline">expand_more</span>
        </div>
        {{-- Filter tanggal --}}
        <input type="date" name="date"
               value="{{ $date }}"
               max="{{ today()->format('Y-m-d') }}"
               class="h-touch-target px-md rounded-xl border border-outline-variant
                      bg-surface-container-lowest focus:border-primary text-body-sm outline-none"
               onchange="this.form.submit()">
        {{-- Reset --}}
        @if($siswaId || $date)
        <a href="{{ route('activity-reports.guru') }}"
           class="h-touch-target px-md flex items-center gap-xs rounded-xl border border-outline-variant
                  text-on-surface-variant hover:bg-surface-container text-body-sm transition-colors">
            <span class="material-symbols-outlined text-[18px]">filter_alt_off</span>
            Reset
        </a>
        @endif
    </form>

    {{-- List laporan --}}
    <div class="space-y-md">
        @forelse($reports as $report)
        @php
            $firstImg = $report->files->firstWhere('mime_type', fn ($m) => str_starts_with($m, 'image/'));
        @endphp
        <a href="{{ route('activity-reports.show', $report) }}"
           class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden
                  shadow-sm flex gap-md hover:bg-surface-container-low transition-colors
                  active:scale-[0.99] duration-100 block">
            {{-- Thumbnail --}}
            <div class="w-24 h-24 md:w-32 md:h-32 shrink-0 bg-surface-container overflow-hidden">
                @if($firstImg)
                    <img src="{{ $firstImg->url }}" alt="{{ $report->judul }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-4xl text-outline"
                              style="font-variation-settings:'FILL' 1">perm_media</span>
                    </div>
                @endif
            </div>

            {{-- Content --}}
            <div class="flex-1 py-md pr-md min-w-0">
                {{-- Siswa --}}
                <div class="flex items-center gap-sm mb-xs">
                    <img src="{{ $report->user->photo_url }}" alt="{{ $report->user->name }}"
                         class="w-6 h-6 rounded-full object-cover">
                    <p class="text-label-md text-on-surface-variant truncate">
                        {{ $report->user->name }}
                        @if($report->user->jurusan)
                            · <span class="text-outline">{{ $report->user->jurusan }}</span>
                        @endif
                    </p>
                </div>

                <p class="text-body-lg font-semibold text-on-surface line-clamp-2 mb-xs">
                    {{ $report->judul }}
                </p>

                @if($report->deskripsi)
                    <p class="text-body-sm text-on-surface-variant line-clamp-2 mb-sm">
                        {{ $report->deskripsi }}
                    </p>
                @endif

                <div class="flex items-center gap-md flex-wrap">
                    <span class="text-label-md text-outline flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                        {{ $report->tanggal->translatedFormat('d M Y') }}
                    </span>
                    <span class="text-label-md text-outline flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[14px]">perm_media</span>
                        {{ $report->files->count() }} file
                    </span>
                    {{-- Type badges --}}
                    @if($report->files->contains(fn($f) => str_starts_with($f->mime_type, 'image/')))
                        <span class="text-label-md px-sm py-0.5 rounded-full bg-primary-container/20 text-primary">Foto</span>
                    @endif
                    @if($report->files->contains(fn($f) => str_starts_with($f->mime_type, 'video/')))
                        <span class="text-label-md px-sm py-0.5 rounded-full bg-secondary-container/30 text-secondary">Video</span>
                    @endif
                    @if($report->files->contains(fn($f) => !str_starts_with($f->mime_type, 'image/') && !str_starts_with($f->mime_type, 'video/')))
                        <span class="text-label-md px-sm py-0.5 rounded-full bg-surface-container text-outline">Dokumen</span>
                    @endif
                </div>
            </div>
        </a>
        @empty
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-xl text-center">
            <span class="material-symbols-outlined text-5xl text-outline block mb-sm"
                  style="font-variation-settings:'FILL' 1">perm_media</span>
            <p class="text-body-lg text-on-surface-variant">
                @if($siswaId || $date)
                    Tidak ada laporan dengan filter ini.
                @else
                    Belum ada laporan kegiatan yang diunggah.
                @endif
            </p>
        </div>
        @endforelse
    </div>

    <div class="mt-lg">{{ $reports->withQueryString()->links() }}</div>

</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="activity" role="guru" />
@endsection
