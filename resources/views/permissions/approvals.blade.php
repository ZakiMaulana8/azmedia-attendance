@extends('layouts.app')
@section('title', 'Approval Izin Siswa')

@section('content')
<div x-data="approvalPage()"
     x-on:keydown.escape.window="closeModal()">

    {{-- ══════════════════════════════════════════════════════════
         HERO HEADER
    ══════════════════════════════════════════════════════════ --}}
    <section class="relative" style="background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 55%, #3B82F6 100%); border-radius: 0 0 28px 28px;">
        <div class="pointer-events-none absolute inset-0 overflow-hidden" style="border-radius: 0 0 28px 28px;">
            <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full" style="background:rgba(255,255,255,0.07)"></div>
            <div class="absolute -bottom-4 -left-4 w-32 h-32 rounded-full" style="background:rgba(255,255,255,0.05)"></div>
        </div>

        <div class="relative z-10 px-5 pt-6 pb-7">
            <div class="flex items-center gap-3 mb-1">
                <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings:'FILL' 1">fact_check</span>
                </div>
                <div>
                    <h1 class="text-white font-extrabold text-[20px] leading-tight">Approval Izin</h1>
                    <p class="text-white/60 text-[12px]">Kelola pengajuan izin siswa magang</p>
                </div>
            </div>

            {{-- Status tabs --}}
            <div class="flex gap-2 mt-5 overflow-x-auto hide-scrollbar pb-1">
                @foreach(['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak', 'semua' => 'Semua'] as $val => $label)
                <a href="{{ route('permissions.approvals', ['status' => $val]) }}"
                   class="px-4 py-1.5 rounded-full text-[12px] font-bold whitespace-nowrap transition-all
                          {{ $status === $val
                              ? 'bg-white text-[#1D4ED8] shadow-md'
                              : 'bg-white/20 text-white hover:bg-white/30' }}">
                    {{ $label }}
                    @if($val === 'menunggu' && $permissions->where('status','menunggu')->count() > 0 && $status !== 'menunggu')
                    @endif
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════
         DAFTAR PENGAJUAN
    ══════════════════════════════════════════════════════════ --}}
    <div class="px-4 pt-5 pb-6 max-w-lg mx-auto">

        @if($permissions->count())
        <p class="text-[12px] text-[#94A3B8] mb-3 font-medium">{{ $permissions->total() }} pengajuan ditemukan</p>
        @endif

        <div class="space-y-3">
            @forelse($permissions as $perm)
            @php
                $statusConf = match($perm->status) {
                    'disetujui' => ['bg'=>'#D1FAE5','text'=>'#065F46','icon'=>'check_circle','iconColor'=>'#059669'],
                    'ditolak'   => ['bg'=>'#FEE2E2','text'=>'#991B1B','icon'=>'cancel','iconColor'=>'#EF4444'],
                    default     => ['bg'=>'#FEF3C7','text'=>'#92400E','icon'=>'pending','iconColor'=>'#F59E0B'],
                };
            @endphp
            <div class="bg-white border border-[#E2E8F0] rounded-2xl p-4 shadow-sm
                        flex items-center gap-3 cursor-pointer hover:shadow-md hover:border-[#BFDBFE] transition-all duration-200
                        active:scale-[0.99]"
                 @click="openModal({{ $perm->id }}, {{ json_encode([
                     'id'               => $perm->id,
                     'nama'             => $perm->user->name,
                     'avatar'           => $perm->user->photo_url,
                     'jurusan'          => $perm->user->jurusan ?? '—',
                     'jenis'            => $perm->jenis_label,
                     'tanggal'          => $perm->tanggal->translatedFormat('d F Y'),
                     'alasan'           => $perm->alasan,
                     'lampiran'         => $perm->lampiran ? asset('storage/'.$perm->lampiran) : null,
                     'status'           => $perm->status,
                     'approver'         => $perm->approver?->name,
                     'approved_at'      => $perm->approved_at?->translatedFormat('d M Y H:i'),
                     'catatan_approver' => $perm->catatan_approver,
                 ]) }})">

                {{-- Avatar --}}
                <div class="w-12 h-12 rounded-full overflow-hidden ring-2 shrink-0"
                     style="ring-color:{{ $statusConf['bg'] }}">
                    <img src="{{ $perm->user->photo_url }}" alt="{{ $perm->user->name }}" class="w-full h-full object-cover">
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <p class="text-[14px] font-bold text-[#0F172A]" style="word-break: break-word; overflow-wrap: break-word; white-space: normal;">{{ $perm->user->name }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full"
                              style="background:{{ $statusConf['bg'] }};color:{{ $statusConf['text'] }}">
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

                {{-- Status badge --}}
                <div class="flex flex-col items-end gap-1 shrink-0">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                         style="background:{{ $statusConf['bg'] }}">
                        <span class="material-symbols-outlined text-[18px]"
                              style="color:{{ $statusConf['iconColor'] }};font-variation-settings:'FILL' 1">
                            {{ $statusConf['icon'] }}
                        </span>
                    </div>
                    <span class="text-[10px] font-bold" style="color:{{ $statusConf['text'] }}">
                        {{ $perm->status_label }}
                    </span>
                </div>
            </div>
            @empty
            {{-- Empty State --}}
            <div class="bg-white border border-dashed border-[#CBD5E1] rounded-2xl p-10 text-center">
                @if($status === 'menunggu')
                <div class="w-16 h-16 bg-[#D1FAE5] rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-[#059669] text-[32px]" style="font-variation-settings:'FILL' 1">check_circle</span>
                </div>
                <p class="text-[15px] font-extrabold text-[#334155]">Semua sudah beres!</p>
                <p class="text-[12px] text-[#94A3B8] mt-1">Tidak ada pengajuan yang menunggu</p>
                @elseif($status === 'disetujui')
                <div class="w-16 h-16 bg-[#EFF6FF] rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-[#2563EB] text-[32px]" style="font-variation-settings:'FILL' 1">fact_check</span>
                </div>
                <p class="text-[15px] font-extrabold text-[#334155]">Belum ada izin disetujui</p>
                <p class="text-[12px] text-[#94A3B8] mt-1">Izin yang disetujui akan tampil di sini</p>
                @elseif($status === 'ditolak')
                <div class="w-16 h-16 bg-[#FEF2F2] rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-[#EF4444] text-[32px]" style="font-variation-settings:'FILL' 1">cancel</span>
                </div>
                <p class="text-[15px] font-extrabold text-[#334155]">Tidak ada izin ditolak</p>
                <p class="text-[12px] text-[#94A3B8] mt-1">Izin yang ditolak akan tampil di sini</p>
                @else
                <div class="w-16 h-16 bg-[#EFF6FF] rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-[#2563EB] text-[32px]" style="font-variation-settings:'FILL' 1">inbox</span>
                </div>
                <p class="text-[15px] font-extrabold text-[#334155]">Tidak ada pengajuan izin</p>
                <p class="text-[12px] text-[#94A3B8] mt-1">Pengajuan izin siswa akan tampil di sini</p>
                @endif
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-5">{{ $permissions->withQueryString()->links() }}</div>
    </div>

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
             x-transition:enter-start="translate-y-full md:scale-95 opacity-0"
             x-transition:enter-end="translate-y-0 md:scale-100 opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="translate-y-0 md:scale-100 opacity-100"
             x-transition:leave-end="translate-y-full md:scale-95 opacity-0"
             class="bg-white w-full md:max-w-md rounded-t-[28px] md:rounded-[24px] shadow-2xl overflow-hidden">

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

            <div class="px-5 py-5 space-y-4 overflow-y-auto max-h-[65vh]">

                {{-- Profil --}}
                <div class="flex items-center gap-4 bg-[#F8FAFF] rounded-2xl p-4 border border-[#DBEAFE]">
                    <img :src="modal.avatar" :alt="modal.nama"
                         class="w-14 h-14 rounded-full object-cover ring-2 ring-[#BFDBFE]">
                    <div class="flex-1 min-w-0">
                        <p class="text-[15px] font-extrabold text-[#0F172A]" style="word-break: break-word; overflow-wrap: break-word; white-space: normal;" x-text="modal.nama"></p>
                        <p class="text-[12px] text-[#64748B]" x-text="modal.jurusan"></p>
                        <span class="inline-block mt-1 text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-[#FEF3C7] text-[#92400E]"
                              x-text="modal.jenis"></span>
                    </div>
                </div>

                {{-- Tanggal & Jenis --}}
                <div class="bg-[#F8FAFF] border border-[#E2E8F0] rounded-2xl overflow-hidden">
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
                            <span class="w-9 h-9 bg-white rounded-xl flex items-center justify-center shadow-sm shrink-0">
                                <span class="material-symbols-outlined text-[#2563EB] text-[18px]">attach_file</span>
                            </span>
                            <span class="text-[13px] font-bold flex-1">Lihat Lampiran</span>
                            <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                        </a>
                    </div>
                </template>

                {{-- Catatan (menunggu) --}}
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
                    <div class="space-y-3">
                        <div class="rounded-2xl p-4 flex items-center justify-center gap-3"
                             :class="modal.status === 'disetujui'
                                 ? 'bg-[#D1FAE5] text-[#065F46]'
                                 : 'bg-[#FEE2E2] text-[#991B1B]'">
                            <span class="material-symbols-outlined text-[24px]" style="font-variation-settings:'FILL' 1"
                                  x-text="modal.status === 'disetujui' ? 'check_circle' : 'cancel'"></span>
                            <span class="text-[14px] font-bold"
                                  x-text="modal.status === 'disetujui' ? 'Izin telah disetujui' : 'Izin telah ditolak'"></span>
                        </div>
                        <template x-if="modal.approver">
                            <p class="text-center text-[12px] text-[#94A3B8]">
                                Diproses oleh <span class="font-bold text-[#334155]" x-text="modal.approver"></span>
                                <template x-if="modal.approved_at">
                                    <span x-text="' · ' + modal.approved_at"></span>
                                </template>
                            </p>
                        </template>
                        <template x-if="modal.catatan_approver">
                            <div class="bg-[#F8FAFF] border border-[#E2E8F0] rounded-2xl px-4 py-3">
                                <p class="text-[10px] font-black uppercase tracking-widest text-[#94A3B8] mb-1">Catatan Pembimbing</p>
                                <p class="text-[13px] text-[#334155]" x-text="modal.catatan_approver"></p>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Footer Action Buttons --}}
            <div class="px-5 pb-6 pt-4 border-t border-[#E2E8F0]"
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
    <x-bottom-nav active="approval" :role="auth()->user()->role" />
@endsection

@push('scripts')
<script>
function approvalPage() {
    return {
        modalOpen:  false,
        processing: false,
        catatan:    '',
        modal: {
            id: null, nama: '', avatar: '', jurusan: '',
            jenis: '', tanggal: '', alasan: '', lampiran: null, status: 'menunggu',
            approver: null, approved_at: null, catatan_approver: null,
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
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'ngrok-skip-browser-warning': 'true',
                    },
                    body: JSON.stringify({ action, catatan: this.catatan }),
                });

                if (!res.ok) {
                    let errMsg = `Terjadi kesalahan (${res.status}). Silakan coba lagi.`;
                    try {
                        const errData = await res.json();
                        if (errData?.message) errMsg = errData.message;
                    } catch (e) {}
                    alert(errMsg);
                    window.location.reload();
                    return;
                }

                const data = await res.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Gagal memproses izin. Silakan coba lagi.');
                }
            } catch (e) {
                console.error(e);
                alert('Terjadi kesalahan koneksi. Silakan coba lagi.');
            } finally {
                this.processing = false;
            }
        },
    };
}
</script>
@endpush
