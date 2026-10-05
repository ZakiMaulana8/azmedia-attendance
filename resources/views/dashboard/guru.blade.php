@extends('layouts.app')
@section('title', 'Dashboard Guru Pembimbing')

@section('content')
<div x-data="guruDashboard()"
     x-on:keydown.escape.window="closeModal()">

    {{-- ══════════════════════════════════════════════════════════
         HERO HEADER — Biru gradient dengan ornamen
    ══════════════════════════════════════════════════════════ --}}
    <section class="relative" style="background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 50%, #3B82F6 100%); border-radius: 0 0 32px 32px;">

        {{-- Ornamen lingkaran dekoratif (overflow hidden hanya untuk ornamen, bukan seluruh section) --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden" style="border-radius: 0 0 32px 32px;">
            <div class="absolute -top-10 -right-10 w-48 h-48 rounded-full" style="background: rgba(255,255,255,0.07)"></div>
            <div class="absolute top-6 -right-4 w-28 h-28 rounded-full" style="background: rgba(255,255,255,0.05)"></div>
            <div class="absolute -bottom-6 -left-6 w-36 h-36 rounded-full" style="background: rgba(255,255,255,0.06)"></div>
            <div class="absolute bottom-4 right-20 w-16 h-16 rounded-full" style="background: rgba(255,255,255,0.08)"></div>
        </div>

        <div class="relative z-10 px-5 pt-6 pb-8">

            {{-- Greeting row — flex responsif, teks tidak terpotong --}}
            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 14px; margin-bottom: 24px;">
                <div style="flex: 1 1 auto; min-width: 0;">
                    <p style="color: rgba(255,255,255,0.65); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; margin: 0 0 4px 0;">Guru Pembimbing</p>
                    <h2 style="color: #ffffff; font-size: 22px; font-weight: 800; line-height: 1.35; letter-spacing: -0.02em; margin: 0; word-break: break-word; overflow-wrap: break-word; white-space: normal; text-overflow: clip; overflow: visible;">
                        Halo, {{ auth()->user()->name }}!
                    </h2>
                    <p style="color: rgba(255,255,255,0.65); font-size: 12px; margin: 4px 0 0 0; word-break: break-word;">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                </div>
                {{-- Kalender — flex-shrink-0, tidak boleh terpotong --}}
                <div style="flex-shrink: 0; text-align: center; border-radius: 16px; padding: 10px 14px; min-width: 72px; background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.28); box-sizing: border-box;">
                    <p style="color: rgba(255,255,255,0.8); font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; margin: 0; line-height: 1.2;">
                        {{ \Carbon\Carbon::now()->translatedFormat('D') }}
                    </p>
                    <p style="color: #ffffff; font-size: 26px; font-weight: 900; line-height: 1.1; margin: 2px 0; letter-spacing: -0.02em;">{{ \Carbon\Carbon::now()->day }}</p>
                    <p style="color: rgba(255,255,255,0.8); font-size: 10px; font-weight: 700; margin: 0; white-space: nowrap;">{{ \Carbon\Carbon::now()->translatedFormat('M Y') }}</p>
                </div>
            </div>

            {{-- Stat cards --}}
            <div class="grid grid-cols-3 gap-3">
                {{-- Hadir --}}
                <div class="rounded-2xl p-3 text-center" style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.2);backdrop-filter:blur(8px)">
                    <p class="text-white font-black text-[28px] leading-none">{{ $todayStats['hadir'] }}</p>
                    <p class="text-white/70 text-[10px] font-semibold mt-1">Hadir</p>
                    <div class="mt-2 w-8 h-8 rounded-full flex items-center justify-center mx-auto" style="background:rgba(255,255,255,0.2)">
                        <span class="material-symbols-outlined text-white text-[16px]" style="font-variation-settings:'FILL' 1">how_to_reg</span>
                    </div>
                </div>
                {{-- Izin --}}
                <div class="rounded-2xl p-3 text-center" style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.18);backdrop-filter:blur(8px)">
                    <p class="text-white font-black text-[28px] leading-none">{{ $todayStats['izin'] }}</p>
                    <p class="text-white/70 text-[10px] font-semibold mt-1">Izin</p>
                    <div class="mt-2 w-8 h-8 rounded-full flex items-center justify-center mx-auto" style="background:rgba(255,255,255,0.2)">
                        <span class="material-symbols-outlined text-white text-[16px]" style="font-variation-settings:'FILL' 1">event_busy</span>
                    </div>
                </div>
                {{-- Alpa --}}
                <div class="rounded-2xl p-3 text-center" style="background:rgba(255,100,100,0.2);border:1px solid rgba(255,100,100,0.3);backdrop-filter:blur(8px)">
                    <p class="text-white font-black text-[28px] leading-none">{{ $todayStats['alpa'] }}</p>
                    <p class="text-white/70 text-[10px] font-semibold mt-1">Alpa</p>
                    <div class="mt-2 w-8 h-8 rounded-full flex items-center justify-center mx-auto" style="background:rgba(255,100,100,0.3)">
                        <span class="material-symbols-outlined text-white text-[16px]" style="font-variation-settings:'FILL' 1">person_off</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════
         BODY CONTENT
    ══════════════════════════════════════════════════════════ --}}
    <div class="px-4 pt-5 pb-6 max-w-screen-xl mx-auto space-y-6">

        {{-- Search & Filter --}}
        <section class="space-y-3">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-[#94A3B8] text-[20px]">search</span>
                <input type="text" placeholder="Cari nama siswa..."
                       class="w-full pl-11 pr-4 py-3 bg-white border border-[#CBD5E1] rounded-2xl
                              focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20 outline-none text-[14px] text-[#0F172A]
                              placeholder-[#94A3B8] shadow-sm transition-all"
                       x-model="searchQuery">
            </div>
            <div class="flex items-center gap-2 overflow-x-auto hide-scrollbar pb-1">
                @foreach(['semua'=>'Semua','RPL'=>'RPL','Broadcast'=>'Broadcast','hadir'=>'Hadir','izin'=>'Izin','alpa'=>'Alpa'] as $val => $label)
                <button @click="activeFilter = '{{ $val }}'"
                        :class="activeFilter === '{{ $val }}'
                            ? 'bg-[#2563EB] text-white shadow-md shadow-blue-500/25'
                            : 'bg-white text-[#64748B] border border-[#CBD5E1] hover:bg-[#EFF6FF] hover:text-[#2563EB] hover:border-[#2563EB]/40'"
                        class="px-4 py-1.5 rounded-full text-[12px] font-semibold whitespace-nowrap transition-all duration-150">
                    {{ $label }}
                </button>
                @endforeach
            </div>
        </section>

        {{-- Daftar Absensi Hari Ini --}}
        <section>
            <div class="flex justify-between items-center mb-3">
                <div>
                    <h2 class="text-[17px] font-extrabold text-[#0F172A]">Absensi Hari Ini</h2>
                    <p class="text-[12px] text-[#64748B]">{{ $todaySiswa->count() }} siswa terdaftar</p>
                </div>
                <a href="{{ route('reports.index') }}"
                   class="flex items-center gap-1 text-[12px] font-bold text-[#2563EB] bg-[#EFF6FF] px-3 py-1.5 rounded-full hover:bg-[#DBEAFE] transition-colors">
                    <span class="material-symbols-outlined text-[14px]">bar_chart</span>
                    Laporan
                </a>
            </div>

            <div class="space-y-2.5">
                @forelse($todaySiswa as $siswa)
                @php
                    $att         = $siswa->todayAttendance();
                    $status      = $att?->status ?? 'alpa';
                    $statusConfig = match($status) {
                        'hadir'     => ['bg'=>'#D1FAE5','text'=>'#065F46','dot'=>'#10B981','label'=>'Hadir'],
                        'terlambat' => ['bg'=>'#FEF3C7','text'=>'#92400E','dot'=>'#F59E0B','label'=>'Terlambat'],
                        'izin'      => ['bg'=>'#EFF6FF','text'=>'#1E40AF','dot'=>'#3B82F6','label'=>'Izin'],
                        default     => ['bg'=>'#FEE2E2','text'=>'#991B1B','dot'=>'#EF4444','label'=>'Alpa'],
                    };
                @endphp
                <div class="bg-white border border-[#E2E8F0] rounded-2xl p-4 shadow-sm flex items-center gap-3
                            hover:shadow-md hover:border-[#BFDBFE] transition-all duration-200"
                     x-show="showSiswa('{{ $siswa->jurusan }}', '{{ $status }}', '{{ strtolower($siswa->name) }}')">
                    <div class="relative shrink-0">
                        <div class="w-11 h-11 rounded-full overflow-hidden ring-2 ring-[#DBEAFE]">
                            <img src="{{ $siswa->photo_url }}" alt="{{ $siswa->name }}" class="w-full h-full object-cover">
                        </div>
                        {{-- Status dot --}}
                        <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full border-2 border-white"
                             style="background-color:{{ $statusConfig['dot'] }}"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[14px] font-bold text-[#0F172A]" style="word-break: break-word; overflow-wrap: break-word; white-space: normal;">{{ $siswa->name }}</p>
                        <p class="text-[12px] text-[#64748B]">{{ $siswa->jurusan ?? '—' }}</p>
                        <p class="text-[11px] text-[#94A3B8] mt-0.5">
                            <span class="material-symbols-outlined text-[12px] align-middle">login</span>
                            {{ $att?->jam_masuk ? \Carbon\Carbon::parse($att->jam_masuk)->format('H:i') : '--:--' }}
                            &nbsp;·&nbsp;
                            <span class="material-symbols-outlined text-[12px] align-middle">logout</span>
                            {{ $att?->jam_pulang ? \Carbon\Carbon::parse($att->jam_pulang)->format('H:i') : '--:--' }}
                        </p>
                    </div>
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full whitespace-nowrap"
                          style="background:{{ $statusConfig['bg'] }};color:{{ $statusConfig['text'] }}">
                        {{ $statusConfig['label'] }}
                    </span>
                </div>
                @empty
                <div class="bg-white border border-dashed border-[#CBD5E1] rounded-2xl p-8 text-center">
                    <div class="w-14 h-14 bg-[#EFF6FF] rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-[#2563EB] text-[28px]" style="font-variation-settings:'FILL' 1">group</span>
                    </div>
                    <p class="text-[14px] font-bold text-[#334155]">Belum ada siswa magang</p>
                    <p class="text-[12px] text-[#94A3B8] mt-1">Data siswa akan tampil di sini</p>
                </div>
                @endforelse
            </div>
        </section>

        {{-- Persetujuan Izin --}}
        <section>
            <div class="flex justify-between items-center mb-3">
                <div class="flex items-center gap-2">
                    <h2 class="text-[17px] font-extrabold text-[#0F172A]">Pengajuan Izin</h2>
                    @if($pendingPermissions->count())
                    <span class="text-[11px] font-black px-2 py-0.5 rounded-full bg-[#FEF3C7] text-[#92400E]">
                        {{ $pendingPermissions->count() }} menunggu
                    </span>
                    @endif
                </div>
                <a href="{{ route('permissions.approvals') }}"
                   class="flex items-center gap-1 text-[12px] font-bold text-[#2563EB] bg-[#EFF6FF] px-3 py-1.5 rounded-full hover:bg-[#DBEAFE] transition-colors">
                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                    Semua
                </a>
            </div>

            @forelse($pendingPermissions as $perm)
            <div class="bg-white border border-[#E2E8F0] rounded-2xl p-4 shadow-sm mb-2.5
                        flex items-center gap-3 cursor-pointer hover:shadow-md hover:border-[#BFDBFE] transition-all duration-200
                        active:scale-[0.99]"
                 x-show="!removedIds.includes({{ $perm->id }})"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click="openModal({{ $perm->id }}, {{ json_encode([
                     'id'          => $perm->id,
                     'nama'        => $perm->user->name,
                     'avatar'      => $perm->user->photo_url,
                     'jurusan'     => $perm->user->jurusan ?? '—',
                     'jenis'       => $perm->jenis_label,
                     'tanggal'     => $perm->tanggal->translatedFormat('d F Y'),
                     'alasan'      => $perm->alasan,
                     'lampiran'    => $perm->lampiran ? asset('storage/'.$perm->lampiran) : null,
                     'status'      => $perm->status,
                 ]) }})">
                <div class="w-11 h-11 rounded-full overflow-hidden ring-2 ring-[#FEF3C7] shrink-0">
                    <img src="{{ $perm->user->photo_url }}" alt="{{ $perm->user->name }}" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[14px] font-bold text-[#0F172A]" style="word-break: break-word; overflow-wrap: break-word; white-space: normal;">{{ $perm->user->name }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-[#FEF3C7] text-[#92400E]">
                            {{ $perm->jenis_label }}
                        </span>
                        @if($perm->user->jurusan)
                        <span class="text-[11px] text-[#94A3B8]">{{ $perm->user->jurusan }}</span>
                        @endif
                    </div>
                    <p class="text-[11px] text-[#94A3B8] mt-0.5">
                        <span class="material-symbols-outlined text-[12px] align-middle">calendar_today</span>
                        {{ $perm->tanggal->translatedFormat('d M Y') }}
                    </p>
                </div>
                <div class="flex flex-col items-center gap-1 shrink-0">
                    <div class="w-8 h-8 rounded-full bg-[#EFF6FF] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#2563EB] text-[18px]">chevron_right</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white border border-dashed border-[#CBD5E1] rounded-2xl p-8 text-center">
                <div class="w-14 h-14 bg-[#D1FAE5] rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-[#059669] text-[28px]" style="font-variation-settings:'FILL' 1">check_circle</span>
                </div>
                <p class="text-[14px] font-bold text-[#334155]">Semua izin telah diproses</p>
                <p class="text-[12px] text-[#94A3B8] mt-1">Tidak ada pengajuan yang menunggu</p>
            </div>
            @endforelse
        </section>

    </div>{{-- end body --}}

    {{-- ══════════════════════════════════════════════════════════
         MODAL DETAIL IZIN
    ══════════════════════════════════════════════════════════ --}}
    <div x-show="modalOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-black/50 flex items-end md:items-center justify-center p-0 md:p-4"
         @click.self="closeModal()"
         style="display:none">

        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="translate-y-full md:translate-y-0 md:scale-95 opacity-0"
             x-transition:enter-end="translate-y-0 md:scale-100 opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="translate-y-0 md:scale-100 opacity-100"
             x-transition:leave-end="translate-y-full md:translate-y-0 md:scale-95 opacity-0"
             class="bg-white w-full md:max-w-md rounded-t-[28px] md:rounded-[24px] shadow-2xl overflow-hidden">

            {{-- Handle bar mobile --}}
            <div class="flex justify-center pt-3 pb-1 md:hidden">
                <div class="w-10 h-1.5 bg-[#CBD5E1] rounded-full"></div>
            </div>

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-5 pt-4 pb-4 border-b border-[#E2E8F0]">
                <div>
                    <p class="text-[16px] font-extrabold text-[#0F172A]">Detail Pengajuan Izin</p>
                    <p class="text-[12px] text-[#64748B]" x-text="modal.tanggal"></p>
                </div>
                <button @click="closeModal()"
                        class="w-9 h-9 rounded-full bg-[#F1F5F9] flex items-center justify-center
                               material-symbols-outlined text-[#64748B] hover:bg-[#E2E8F0] transition-colors text-[20px]">
                    close
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="px-5 py-5 space-y-4 overflow-y-auto max-h-[65vh]">

                {{-- Profil siswa --}}
                <div class="flex items-center gap-4 bg-[#F8FAFF] rounded-2xl p-4 border border-[#DBEAFE]">
                    <img :src="modal.avatar" :alt="modal.nama"
                         class="w-14 h-14 rounded-full object-cover ring-2 ring-[#BFDBFE]">
                    <div>
                        <p class="text-[15px] font-extrabold text-[#0F172A]" x-text="modal.nama"></p>
                        <p class="text-[12px] text-[#64748B]" x-text="modal.jurusan"></p>
                        <div class="mt-1">
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-[#FEF3C7] text-[#92400E]"
                                  x-text="modal.jenis"></span>
                        </div>
                    </div>
                </div>

                {{-- Detail rows --}}
                <div class="bg-[#F8FAFF] rounded-2xl border border-[#E2E8F0] overflow-hidden">
                    <div class="flex justify-between items-center px-4 py-3 border-b border-[#E2E8F0]">
                        <span class="text-[12px] text-[#64748B] flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[#94A3B8] text-[16px]">category</span>
                            Jenis Izin
                        </span>
                        <span class="text-[13px] font-bold text-[#0F172A]" x-text="modal.jenis"></span>
                    </div>
                    <div class="flex justify-between items-center px-4 py-3">
                        <span class="text-[12px] text-[#64748B] flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[#94A3B8] text-[16px]">calendar_today</span>
                            Tanggal
                        </span>
                        <span class="text-[13px] font-bold text-[#0F172A]" x-text="modal.tanggal"></span>
                    </div>
                </div>

                {{-- Alasan --}}
                <div>
                    <p class="text-[11px] font-black uppercase tracking-widest text-[#94A3B8] mb-2">Alasan</p>
                    <div class="bg-[#F8FAFF] border border-[#E2E8F0] rounded-2xl px-4 py-3">
                        <p class="text-[13px] text-[#334155] leading-relaxed" x-text="modal.alasan"></p>
                    </div>
                </div>

                {{-- Lampiran --}}
                <template x-if="modal.lampiran">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-widest text-[#94A3B8] mb-2">Lampiran</p>
                        <a :href="modal.lampiran" target="_blank"
                           class="flex items-center gap-3 bg-[#EFF6FF] border border-[#BFDBFE] rounded-2xl px-4 py-3
                                  text-[#2563EB] hover:bg-[#DBEAFE] transition-colors">
                            <span class="w-9 h-9 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                <span class="material-symbols-outlined text-[#2563EB] text-[18px]">attach_file</span>
                            </span>
                            <span class="text-[13px] font-bold flex-1">Lihat Lampiran</span>
                            <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                        </a>
                    </div>
                </template>

                {{-- Catatan (hanya saat menunggu) --}}
                <div x-show="modal.status === 'menunggu'">
                    <p class="text-[11px] font-black uppercase tracking-widest text-[#94A3B8] mb-2">Catatan (opsional)</p>
                    <textarea x-model="catatan" rows="3"
                              placeholder="Tambahkan catatan untuk siswa..."
                              class="w-full p-4 rounded-2xl border border-[#CBD5E1] bg-white
                                     focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20
                                     text-[13px] resize-none outline-none text-[#334155] transition-all"></textarea>
                </div>

                {{-- Status sudah diproses --}}
                <template x-if="modal.status !== 'menunggu'">
                    <div class="rounded-2xl p-4 flex items-center justify-center gap-3"
                         :class="modal.status === 'disetujui'
                             ? 'bg-[#D1FAE5] text-[#065F46]'
                             : 'bg-[#FEE2E2] text-[#991B1B]'">
                        <span class="material-symbols-outlined text-[24px]" style="font-variation-settings:'FILL' 1"
                              x-text="modal.status === 'disetujui' ? 'check_circle' : 'cancel'"></span>
                        <span class="text-[14px] font-bold"
                              x-text="modal.status === 'disetujui' ? 'Izin telah disetujui' : 'Izin telah ditolak'"></span>
                    </div>
                </template>
            </div>

            {{-- Modal Footer --}}
            <div class="px-5 pb-6 pt-4 border-t border-[#E2E8F0] space-y-3"
                 x-show="modal.status === 'menunggu'">
                <div class="grid grid-cols-2 gap-3">
                    <button @click="submitApproval('ditolak')"
                            :disabled="processing"
                            class="h-12 rounded-2xl border-2 border-[#EF4444] text-[#EF4444] font-bold text-[14px]
                                   flex items-center justify-center gap-2 hover:bg-[#FEE2E2]
                                   active:scale-95 transition-all disabled:opacity-50">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                        Tolak
                    </button>
                    <button @click="submitApproval('disetujui')"
                            :disabled="processing"
                            class="h-12 rounded-2xl bg-[#2563EB] text-white font-bold text-[14px]
                                   flex items-center justify-center gap-2 hover:bg-[#1D4ED8]
                                   active:scale-95 transition-all disabled:opacity-50 shadow-lg shadow-blue-500/30">
                        <span class="material-symbols-outlined text-[18px]" x-show="!processing" style="font-variation-settings:'FILL' 1">check_circle</span>
                        <span class="material-symbols-outlined animate-spin text-[18px]" x-show="processing">progress_activity</span>
                        <span x-text="processing ? 'Memproses...' : 'Setujui'">Setujui</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="dashboard" role="guru" />
@endsection

@push('scripts')
<script>
function guruDashboard() {
    return {
        activeFilter: 'semua',
        searchQuery:  '',
        removedIds:   [],
        modalOpen:    false,
        processing:   false,
        catatan:      '',
        modal: {
            id: null, nama: '', avatar: '', jurusan: '',
            jenis: '', tanggal: '', alasan: '', lampiran: null, status: 'menunggu',
        },

        showSiswa(jurusan, status, nama) {
            const q = this.searchQuery.toLowerCase();
            if (q && !nama.includes(q)) return false;
            if (this.activeFilter === 'semua')      return true;
            if (this.activeFilter === 'RPL')        return jurusan === 'RPL';
            if (this.activeFilter === 'Broadcast')  return jurusan === 'Broadcast';
            if (this.activeFilter === 'hadir') return status === 'hadir' || status === 'terlambat';
            if (this.activeFilter === 'izin')  return status === 'izin';
            if (this.activeFilter === 'alpa')  return status === 'alpa';
            return true;
        },

        openModal(id, data) {
            this.modal     = data;
            this.catatan   = '';
            this.modalOpen = true;
            document.body.classList.add('overflow-hidden');
        },

        closeModal() {
            this.modalOpen = false;
            document.body.classList.remove('overflow-hidden');
        },

        async submitApproval(action) {
            this.processing = true;
            try {
                const res = await fetch(`/permissions/${this.modal.id}/approve`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ action, catatan: this.catatan }),
                });
                const data = await res.json();
                if (data.success) {
                    this.removedIds.push(this.modal.id);
                    this.closeModal();
                }
            } catch (e) {
                console.error(e);
            } finally {
                this.processing = false;
            }
        },
    };
}
</script>
@endpush
