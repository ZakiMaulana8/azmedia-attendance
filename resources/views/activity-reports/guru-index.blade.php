@extends('layouts.app')
@section('title', 'Laporan Kegiatan Siswa')

@section('content')
<div class="max-w-6xl mx-auto p-4 sm:p-6 space-y-6">

    {{-- ══════════════════════════════════════════════════════════
         HERO HEADER & FILTER CARD
    ══════════════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden shadow-lg"
             style="background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 55%, #3B82F6 100%); border-radius: 24px; padding: 28px 24px;">
        {{-- Decorative circles --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden" style="border-radius: 24px;">
            <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full" style="background:rgba(255,255,255,0.08); filter: blur(20px);"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full" style="background:rgba(255,255,255,0.06); filter: blur(16px);"></div>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">

            {{-- Judul & Subtitle --}}
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-md"
                     style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35); backdrop-filter: blur(8px);">
                    <span class="material-symbols-outlined text-white text-[26px]" style="font-variation-settings:'FILL' 1">photo_library</span>
                </div>
                <div>
                    <h1 class="text-white font-extrabold text-[22px] sm:text-[24px] leading-tight tracking-tight">
                        Laporan Kegiatan Siswa
                    </h1>
                    <p class="text-white/80 text-[13px] font-medium mt-0.5">
                        Dokumentasi dan jurnal kegiatan magang seluruh siswa
                    </p>
                </div>
            </div>

            {{-- Filter Form Integrated in Hero Card --}}
            <form method="GET" action="{{ route('activity-reports.guru') }}" class="flex items-center gap-2.5 flex-wrap shrink-0">
                {{-- Filter siswa --}}
                <div class="relative min-w-[180px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-white/80 text-[18px] pointer-events-none">person_search</span>
                    <select name="siswa"
                            class="w-full h-11 pl-9 pr-8 rounded-xl text-white text-[13px] font-bold focus:outline-none appearance-none cursor-pointer shadow-sm transition-all"
                            style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35); backdrop-filter: blur(8px);"
                            onchange="this.form.submit()">
                        <option value="" class="text-slate-800 font-semibold">Semua Siswa</option>
                        @foreach($siswaList as $s)
                            <option value="{{ $s->id }}" @selected($siswaId == $s->id) class="text-slate-800 font-semibold">
                                {{ $s->name }}@if($s->jurusan) ({{ $s->jurusan }})@endif
                            </option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-white/80 text-[18px]">expand_more</span>
                </div>

                {{-- Filter tanggal --}}
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-white/80 text-[18px] pointer-events-none">calendar_today</span>
                    <input type="date" name="date"
                           value="{{ $date }}"
                           max="{{ today()->format('Y-m-d') }}"
                           class="h-11 pl-9 pr-4 rounded-xl text-white text-[13px] font-bold focus:outline-none shadow-sm cursor-pointer transition-all"
                           style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35); backdrop-filter: blur(8px);"
                           onchange="this.form.submit()">
                </div>

                {{-- Reset --}}
                @if($siswaId || $date)
                <a href="{{ route('activity-reports.guru') }}"
                   class="h-11 px-3.5 flex items-center gap-1.5 rounded-xl bg-white/20 hover:bg-white/30 text-white border border-white/30 text-[13px] font-bold transition-all shadow-sm backdrop-blur-md">
                    <span class="material-symbols-outlined text-[18px]">filter_alt_off</span>
                    Reset
                </a>
                @endif
            </form>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════
         LIST LAPORAN
    ══════════════════════════════════════════════════════════ --}}
    <div class="max-w-4xl mx-auto space-y-4 pt-2">

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
