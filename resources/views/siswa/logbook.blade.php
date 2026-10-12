@extends('layouts.app')
@section('title', 'Logbook ' . $siswa->name)

@section('content')
<div class="max-w-3xl mx-auto p-4 sm:p-6 pb-28 sm:pb-28 space-y-5">

    {{-- Tombol kembali --}}
    <a href="{{ route('dashboard') }}"
       class="inline-flex items-center gap-2 bg-white border border-[#DBEAFE] rounded-2xl px-3.5 py-2 text-[13px] font-bold text-[#1E40AF] shadow-sm hover:border-blue-300 active:scale-95 transition-all">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
        <span>Dashboard</span>
    </a>

    {{-- Profil siswa --}}
    <section class="relative overflow-hidden shadow-lg"
             style="background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 55%, #3B82F6 100%); border-radius: 24px; padding: 22px 20px;">
        <div class="relative z-10 flex items-center gap-4">
            <div class="w-16 h-16 rounded-full overflow-hidden shrink-0 ring-4 ring-white/30 bg-white/20">
                <img src="{{ $siswa->photo_url }}" alt="{{ $siswa->name }}" class="w-full h-full object-cover">
            </div>
            <div class="min-w-0">
                <p class="text-white/80 text-[11px] font-bold uppercase tracking-wider mb-0.5">Riwayat Absensi &amp; Logbook</p>
                <h1 class="text-white font-extrabold text-[20px] leading-tight tracking-tight" style="overflow-wrap:anywhere;">{{ $siswa->name }}</h1>
                <p class="text-white/85 text-[12px] font-medium mt-0.5" style="overflow-wrap:anywhere;">
                    {{ $siswa->jurusan ?: 'Siswa Magang' }}{{ $siswa->asal_sekolah ? ' · ' . $siswa->asal_sekolah : '' }}
                </p>
            </div>
        </div>
    </section>

    {{-- Daftar riwayat --}}
    <div class="space-y-5">
        @forelse($attendances as $att)
            @php
                $jamMasuk  = $att->jam_masuk  ? \Carbon\Carbon::parse($att->jam_masuk)->format('H:i')  : '--:--';
                $jamPulang = $att->jam_pulang ? \Carbon\Carbon::parse($att->jam_pulang)->format('H:i') : '--:--';
                $statusMap = [
                    'hadir'     => ['bg' => '#D1FAE5', 'text' => '#065F46', 'label' => 'Hadir'],
                    'terlambat' => ['bg' => '#FEF3C7', 'text' => '#92400E', 'label' => 'Terlambat'],
                    'izin'      => ['bg' => '#EFF6FF', 'text' => '#1E40AF', 'label' => 'Izin'],
                ];
                $st = $statusMap[$att->status] ?? ['bg' => '#FEE2E2', 'text' => '#991B1B', 'label' => 'Alpa'];
            @endphp

            <article class="bg-white border border-[#DBEAFE] rounded-[20px] shadow-sm" style="padding: 20px;">
                {{-- Tanggal & status --}}
                <div class="flex items-center justify-between gap-3 flex-wrap" style="margin-bottom: 14px;">
                    <div class="flex items-center gap-2 text-[14px] font-extrabold text-[#0F172A]">
                        <span class="material-symbols-outlined text-[20px] text-[#2563EB]">calendar_today</span>
                        {{ $att->tanggal->translatedFormat('l, d F Y') }}
                    </div>
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full" style="background: {{ $st['bg'] }}; color: {{ $st['text'] }};">
                        {{ $st['label'] }}
                    </span>
                </div>

                {{-- Jam --}}
                <div class="flex items-center gap-4 flex-wrap bg-[#F8FAFC] border border-[#E2E8F0] rounded-[14px] text-[12px] font-semibold text-[#334155]"
                     style="padding: 10px 14px; margin-bottom: 16px;">
                    <span class="inline-flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-[#2563EB]">login</span>
                        Masuk: <strong class="text-[#0F172A]">{{ $jamMasuk }}</strong>
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-amber-600">logout</span>
                        Pulang: <strong class="text-[#0F172A]">{{ $jamPulang }}</strong>
                    </span>
                    @if($att->keterangan)
                        <span class="text-[#64748B]">· {{ $att->keterangan }}</span>
                    @endif
                </div>

                {{-- Logbook --}}
                <div style="margin-bottom: 16px;">
                    <p class="flex items-center gap-1.5 text-[11px] font-black uppercase tracking-widest text-[#94A3B8]" style="margin-bottom: 8px;">
                        <span class="material-symbols-outlined text-[16px]">edit_note</span> Logbook Aktivitas Harian
                    </p>
                    @if($att->logbook)
                        <div class="bg-[#F8FAFF] border border-[#E2E8F0] rounded-2xl text-[13px] text-[#334155]"
                             style="padding: 14px 16px; line-height: 1.6; white-space: pre-line; overflow-wrap: anywhere;">{{ $att->logbook }}</div>
                    @else
                        <p class="text-[12.5px] text-[#94A3B8] italic">Belum ada logbook (siswa belum absen pulang).</p>
                    @endif
                </div>

                {{-- Foto --}}
                <div class="grid grid-cols-2 gap-3">
                    @foreach([['Foto Masuk', $att->foto_masuk], ['Foto Pulang', $att->foto_pulang]] as [$label, $path])
                        <div>
                            <p class="text-[11px] font-black uppercase tracking-widest text-[#94A3B8]" style="margin-bottom: 8px;">{{ $label }}</p>
                            @if($path)
                                <a href="{{ asset('storage/' . $path) }}" target="_blank" rel="noopener"
                                   class="block rounded-2xl overflow-hidden border border-[#E2E8F0] bg-[#0F172A]" style="aspect-ratio: 4/3;">
                                    <img src="{{ asset('storage/' . $path) }}" alt="{{ $label }} {{ $siswa->name }}"
                                         class="w-full h-full object-cover" style="transform: scaleX(-1);" loading="lazy">
                                </a>
                            @else
                                <div class="rounded-2xl border border-dashed border-[#CBD5E1] bg-[#F8FAFC] flex items-center justify-center text-[12px] text-[#94A3B8]"
                                     style="aspect-ratio: 4/3;">Tidak ada foto</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </article>
        @empty
            <div class="bg-white border border-dashed border-[#CBD5E1] rounded-2xl p-8 text-center">
                <div class="w-14 h-14 bg-[#EFF6FF] rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-[#2563EB] text-[28px]">history</span>
                </div>
                <p class="text-[14px] font-bold text-[#334155]">Belum ada riwayat absensi</p>
                <p class="text-[12px] text-[#94A3B8] mt-1">Logbook akan tampil di sini setelah siswa absen pulang.</p>
            </div>
        @endforelse
    </div>

    @if($attendances->hasPages())
        <div>{{ $attendances->links() }}</div>
    @endif
</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="dashboard" :role="$viewerRole" />
@endsection
