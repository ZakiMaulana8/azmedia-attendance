@extends('layouts.app')
@section('title', 'Approval Izin Siswa')

@section('content')
<div class="px-md max-w-lg mx-auto pt-lg pb-4"
     x-data="approvalPage()"
     x-on:keydown.escape.window="closeModal()">

    {{-- Header --}}
    <section class="mb-lg">
        <h2 class="text-headline-lg-mobile font-bold text-on-surface">Approval Izin</h2>
        <p class="text-body-sm text-on-surface-variant">Kelola pengajuan izin siswa magang</p>
    </section>

    {{-- Filter tab status --}}
    <div class="flex gap-sm mb-lg overflow-x-auto hide-scrollbar">
        @foreach(['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak', 'semua' => 'Semua'] as $val => $label)
        <a href="{{ route('permissions.approvals', ['status' => $val]) }}"
           class="px-md py-1.5 rounded-full text-label-md whitespace-nowrap transition-colors
                  {{ $status === $val
                      ? 'bg-primary text-on-primary'
                      : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- List --}}
    <div class="space-y-sm">
        @forelse($permissions as $perm)
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm
                    flex items-center gap-3 cursor-pointer hover:bg-surface-container-low transition-colors
                    active:scale-[0.99] duration-100"
             @click="openModal({{ $perm->id }}, {{ json_encode([
                 'id'       => $perm->id,
                 'nama'     => $perm->user->name,
                 'avatar'   => $perm->user->photo_url,
                 'jurusan'  => $perm->user->jurusan ?? '—',
                 'jenis'    => $perm->jenis_label,
                 'tanggal'  => $perm->tanggal->translatedFormat('d F Y'),
                 'alasan'   => $perm->alasan,
                 'lampiran' => $perm->lampiran ? asset('storage/'.$perm->lampiran) : null,
                 'status'   => $perm->status,
             ]) }})">
            <div class="w-12 h-12 rounded-xl overflow-hidden bg-surface-container-high shrink-0">
                <img src="{{ $perm->user->photo_url }}" alt="{{ $perm->user->name }}"
                     class="w-full h-full object-cover">
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-body-lg font-semibold text-on-surface truncate">{{ $perm->user->name }}</p>
                <p class="text-body-sm text-on-surface-variant">
                    {{ $perm->jenis_label }}
                    @if($perm->user->jurusan) · {{ $perm->user->jurusan }} @endif
                </p>
                <p class="text-label-md text-outline">{{ $perm->tanggal->translatedFormat('d M Y') }}</p>
            </div>
            @php
                $sc = match($perm->status) {
                    'disetujui' => 'bg-primary-container text-on-primary-container',
                    'ditolak'   => 'bg-error-container text-on-error-container',
                    default     => 'bg-secondary-container text-on-secondary-container',
                };
            @endphp
            <span class="text-status-badge font-bold px-sm py-1 rounded-full whitespace-nowrap {{ $sc }}">
                {{ $perm->status_label }}
            </span>
        </div>
        @empty
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-xl text-center">
            <span class="material-symbols-outlined text-4xl text-outline block mb-sm">fact_check</span>
            <p class="text-body-lg text-on-surface-variant">Tidak ada pengajuan izin</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-lg">{{ $permissions->withQueryString()->links() }}</div>

    {{-- ── Modal Detail ──────────────────────────────────────────────────────── --}}
    <div x-show="modalOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-black/40 flex items-end md:items-center justify-center p-0 md:p-md"
         @click.self="closeModal()"
         style="display:none">

        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="translate-y-full md:scale-95 opacity-0"
             x-transition:enter-end="translate-y-0 md:scale-100 opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="translate-y-0 md:scale-100 opacity-100"
             x-transition:leave-end="translate-y-full md:scale-95 opacity-0"
             class="bg-surface w-full md:max-w-md rounded-t-2xl md:rounded-xl shadow-xl overflow-hidden">

            <div class="flex justify-center pt-sm pb-xs md:hidden">
                <div class="w-10 h-1 bg-outline-variant rounded-full"></div>
            </div>

            <div class="flex items-center justify-between px-md pt-md pb-sm border-b border-outline-variant">
                <p class="text-headline-md font-semibold text-on-surface">Detail Pengajuan Izin</p>
                <button @click="closeModal()"
                        class="material-symbols-outlined text-outline hover:text-on-surface
                               p-1 rounded-full hover:bg-surface-container transition-colors">
                    close
                </button>
            </div>

            <div class="px-md py-lg space-y-md overflow-y-auto max-h-[70vh]">
                <div class="flex items-center gap-md">
                    <img :src="modal.avatar" :alt="modal.nama"
                         class="w-14 h-14 rounded-full object-cover border-2 border-primary-container">
                    <div>
                        <p class="text-body-lg font-semibold text-on-surface" x-text="modal.nama"></p>
                        <p class="text-body-sm text-on-surface-variant" x-text="modal.jurusan"></p>
                    </div>
                </div>

                <div class="bg-surface-container-low rounded-xl divide-y divide-outline-variant overflow-hidden">
                    <div class="flex justify-between px-md py-sm">
                        <span class="text-body-sm text-on-surface-variant">Jenis Izin</span>
                        <span class="text-body-sm font-semibold text-on-surface" x-text="modal.jenis"></span>
                    </div>
                    <div class="flex justify-between px-md py-sm">
                        <span class="text-body-sm text-on-surface-variant">Tanggal</span>
                        <span class="text-body-sm font-semibold text-on-surface" x-text="modal.tanggal"></span>
                    </div>
                </div>

                <div class="space-y-xs">
                    <p class="text-label-md text-on-surface-variant uppercase tracking-wider">Alasan</p>
                    <div class="bg-surface-container-low rounded-xl px-md py-sm">
                        <p class="text-body-sm text-on-surface leading-relaxed" x-text="modal.alasan"></p>
                    </div>
                </div>

                <template x-if="modal.lampiran">
                    <div class="space-y-xs">
                        <p class="text-label-md text-on-surface-variant uppercase tracking-wider">Lampiran</p>
                        <a :href="modal.lampiran" target="_blank"
                           class="flex items-center gap-sm bg-surface-container-low rounded-xl px-md py-sm
                                  text-primary hover:bg-surface-container transition-colors">
                            <span class="material-symbols-outlined text-[20px]">attach_file</span>
                            <span class="text-body-sm font-semibold">Lihat Lampiran</span>
                            <span class="material-symbols-outlined text-[16px] ml-auto">open_in_new</span>
                        </a>
                    </div>
                </template>

                <div class="space-y-xs" x-show="modal.status === 'menunggu'">
                    <p class="text-label-md text-on-surface-variant uppercase tracking-wider">Catatan (opsional)</p>
                    <textarea x-model="catatan" rows="3"
                              placeholder="Tambahkan catatan untuk siswa..."
                              class="w-full p-md rounded-xl border border-outline-variant bg-surface
                                     focus:border-primary focus:ring-1 focus:ring-primary
                                     text-body-sm resize-none outline-none"></textarea>
                </div>

                <template x-if="modal.status !== 'menunggu'">
                    <div class="flex items-center justify-center gap-sm p-md rounded-xl"
                         :class="modal.status === 'disetujui'
                             ? 'bg-primary-container/20 text-primary'
                             : 'bg-error-container/20 text-error'">
                        <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1"
                              x-text="modal.status === 'disetujui' ? 'check_circle' : 'cancel'"></span>
                        <span class="text-body-sm font-semibold"
                              x-text="modal.status === 'disetujui' ? 'Izin telah disetujui' : 'Izin telah ditolak'"></span>
                    </div>
                </template>
            </div>

            <div class="px-md pb-lg pt-sm border-t border-outline-variant space-y-sm"
                 x-show="modal.status === 'menunggu'">
                <div class="grid grid-cols-2 gap-sm">
                    <button @click="submitApproval('ditolak')"
                            :disabled="processing"
                            class="h-touch-target rounded-xl border border-error text-error font-semibold
                                   flex items-center justify-center gap-sm hover:bg-error-container/20
                                   active:scale-95 transition-all disabled:opacity-50">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                        Tolak
                    </button>
                    <button @click="submitApproval('disetujui')"
                            :disabled="processing"
                            class="h-touch-target rounded-xl bg-primary text-on-primary font-semibold
                                   flex items-center justify-center gap-sm hover:bg-primary/90
                                   active:scale-95 transition-all disabled:opacity-50">
                        <span class="material-symbols-outlined text-[18px]" x-show="!processing">check</span>
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
    <x-bottom-nav active="approval" role="guru" />
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
                    const text = await res.text();
                    console.error('Server error:', res.status, text);
                    alert(`Terjadi kesalahan (${res.status}). Silakan coba lagi.`);
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
