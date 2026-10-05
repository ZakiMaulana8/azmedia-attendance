@extends('layouts.app')
@section('title', 'Laporan Kegiatan Siswa')

@section('content')
<div>

    {{-- ══════════════════════════════════════════════════════════
         HERO HEADER
    ══════════════════════════════════════════════════════════ --}}
    <section class="relative" style="background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 55%, #3B82F6 100%); border-radius: 0 0 28px 28px;">
        <div class="pointer-events-none absolute inset-0 overflow-hidden" style="border-radius: 0 0 28px 28px;">
            <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full" style="background:rgba(255,255,255,0.07)"></div>
            <div class="absolute -bottom-4 -left-6 w-28 h-28 rounded-full" style="background:rgba(255,255,255,0.05)"></div>
        </div>

        <div class="relative z-10 px-5 pt-6 pb-7">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-white text-[22px]" style="font-variation-settings:'FILL' 1">photo_library</span>
                </div>
                <div>
                    <h1 class="text-white font-extrabold text-[20px] leading-tight">Laporan Kegiatan</h1>
                    <p class="text-white/60 text-[12px]">Dokumentasi kegiatan magang seluruh siswa</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════
         FILTER BAR
    ══════════════════════════════════════════════════════════ --}}
    <div class="px-4 pt-4 pb-3 max-w-screen-xl mx-auto">
        <form method="GET" action="{{ route('activity-reports.guru') }}" class="flex gap-2 flex-wrap">

            {{-- Filter siswa --}}
            <div class="flex-1 min-w-40 relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#94A3B8] text-[18px] pointer-events-none">person_search</span>
                <select name="siswa"
                        class="w-full h-11 pl-9 pr-8 rounded-2xl border border-[#CBD5E1] bg-white
                               text-[#0F172A] text-[13px] font-semibold focus:outline-none
                               focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20 appearance-none
                               shadow-sm cursor-pointer transition-all"
                        onchange="this.form.submit()">
                    <option value="">Semua Siswa</option>
                    @foreach($siswaList as $s)
                        <option value="{{ $s->id }}" @selected($siswaId == $s->id)>
                            {{ $s->name }}@if($s->jurusan) ({{ $s->jurusan }})@endif
                        </option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-[#94A3B8] text-[18px]">expand_more</span>
            </div>

            {{-- Filter tanggal --}}
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#94A3B8] text-[18px] pointer-events-none">calendar_today</span>
                <input type="date" name="date"
                       value="{{ $date }}"
                       max="{{ today()->format('Y-m-d') }}"
                       class="h-11 pl-9 pr-4 rounded-2xl border border-[#CBD5E1] bg-white
                              text-[#0F172A] text-[13px] font-semibold focus:outline-none
                              focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20
                              shadow-sm cursor-pointer transition-all"
                       onchange="this.form.submit()">
            </div>

            {{-- Reset --}}
            @if($siswaId || $date)
            <a href="{{ route('activity-reports.guru') }}"
               class="h-11 px-4 flex items-center gap-1.5 rounded-2xl border border-[#CBD5E1] bg-white
                      text-[#64748B] text-[13px] font-semibold hover:bg-[#F1F5F9] transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">filter_alt_off</span>
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         LIST LAPORAN
    ══════════════════════════════════════════════════════════ --}}
    <div class="px-4 pb-6 max-w-screen-xl mx-auto space-y-3">

        @forelse($reports as $report)
        @php
            $firstImg = $report->files->firstWhere('mime_type', fn ($m) => str_starts_with($m, 'image/'));
        @endphp
        <a href="{{ route('activity-reports.show', $report) }}"
           class="bg-white border border-[#E2E8F0] rounded-2xl overflow-hidden shadow-sm
                  flex gap-0 hover:shadow-md hover:border-[#BFDBFE] transition-all duration-200
                  active:scale-[0.99] block">

            {{-- Thumbnail --}}
            <div class="w-24 h-24 shrink-0 bg-[#F1F5F9] overflow-hidden rounded-l-2xl">
                @if($firstImg)
                    <img src="{{ $firstImg->url }}" alt="{{ $report->judul }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-[36px] text-[#CBD5E1]"
                              style="font-variation-settings:'FILL' 1">perm_media</span>
                    </div>
                @endif
            </div>

            {{-- Konten --}}
            <div class="flex-1 py-3 px-4 min-w-0">
                {{-- Nama siswa --}}
                <div class="flex items-center gap-2 mb-1">
                    <img src="{{ $report->user->photo_url }}" alt="{{ $report->user->name }}"
                         class="w-5 h-5 rounded-full object-cover shrink-0 ring-1 ring-[#DBEAFE]">
                    <p class="text-[11px] text-[#64748B] font-semibold" style="word-break: break-word; overflow-wrap: break-word; white-space: normal;">
                        {{ $report->user->name }}
                        @if($report->user->jurusan)
                            <span class="text-[#CBD5E1] mx-0.5">·</span>
                            <span class="text-[#94A3B8]">{{ $report->user->jurusan }}</span>
                        @endif
                    </p>
                </div>

                {{-- Judul --}}
                <p class="text-[14px] font-bold text-[#0F172A] line-clamp-1 mb-1">{{ $report->judul }}</p>

                @if($report->deskripsi)
                <p class="text-[12px] text-[#64748B] line-clamp-1 mb-2">{{ $report->deskripsi }}</p>
                @endif

                {{-- Meta & badges --}}
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-[11px] text-[#94A3B8] flex items-center gap-1">
                        <span class="material-symbols-outlined text-[12px]">calendar_today</span>
                        {{ $report->tanggal->translatedFormat('d M Y') }}
                    </span>
                    <span class="text-[11px] text-[#94A3B8] flex items-center gap-1">
                        <span class="material-symbols-outlined text-[12px]">perm_media</span>
                        {{ $report->files->count() }} file
                    </span>
                    @if($report->files->contains(fn($f) => str_starts_with($f->mime_type, 'image/')))
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-[#EFF6FF] text-[#2563EB]">Foto</span>
                    @endif
                    @if($report->files->contains(fn($f) => str_starts_with($f->mime_type, 'video/')))
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-[#EDE9FE] text-[#7C3AED]">Video</span>
                    @endif
                    @if($report->files->contains(fn($f) => !str_starts_with($f->mime_type, 'image/') && !str_starts_with($f->mime_type, 'video/')))
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-[#F1F5F9] text-[#64748B]">Dokumen</span>
                    @endif
                </div>
            </div>

            {{-- Chevron --}}
            <div class="flex items-center pr-3">
                <span class="material-symbols-outlined text-[#CBD5E1] text-[20px]">chevron_right</span>
            </div>
        </a>
        @empty
        <div class="bg-white border border-dashed border-[#CBD5E1] rounded-2xl p-10 text-center">
            <div class="w-16 h-16 bg-[#EFF6FF] rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-[#2563EB] text-[32px]"
                      style="font-variation-settings:'FILL' 1">perm_media</span>
            </div>
            <p class="text-[15px] font-extrabold text-[#334155]">
                @if($siswaId || $date)
                    Tidak ada laporan dengan filter ini
                @else
                    Belum ada laporan kegiatan
                @endif
            </p>
            <p class="text-[12px] text-[#94A3B8] mt-1">
                @if($siswaId || $date)
                    Coba ubah atau reset filter di atas
                @else
                    Laporan siswa akan tampil di sini
                @endif
            </p>
        </div>
        @endforelse

        <div class="mt-4">{{ $reports->withQueryString()->links() }}</div>
    </div>

</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="activity" role="guru" />
@endsection
