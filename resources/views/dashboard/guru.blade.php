@extends('layouts.app')
@section('title', 'Dashboard Guru Pembimbing')

@section('content')
<div class="mt-lg px-md max-w-screen-xl mx-auto space-y-lg"
     x-data="guruDashboard()"
     x-on:keydown.escape.window="closeModal()">

    {{-- Greeting --}}
    <section>
        <h2 class="text-headline-lg-mobile font-bold text-on-surface">
            Halo, {{ auth()->user()->name }}
        </h2>
        <p class="text-body-sm text-on-surface-variant">
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </p>
    </section>

    {{-- Summary Cards --}}
    <section class="grid grid-cols-3 gap-sm">
        <div class="bg-surface-container-lowest p-md rounded-xl border border-outline-variant shadow-sm flex flex-col items-center">
            <span class="text-primary text-headline-lg-mobile font-bold">{{ $todayStats['hadir'] }}</span>
            <span class="text-label-md text-on-surface-variant">Hadir</span>
        </div>
        <div class="bg-surface-container-lowest p-md rounded-xl border border-outline-variant shadow-sm flex flex-col items-center">
            <span class="text-secondary text-headline-lg-mobile font-bold">{{ $todayStats['izin'] }}</span>
            <span class="text-label-md text-on-surface-variant">Izin</span>
        </div>
        <div class="bg-surface-container-lowest p-md rounded-xl border border-outline-variant shadow-sm flex flex-col items-center">
            <span class="text-error text-headline-lg-mobile font-bold">{{ $todayStats['alpa'] }}</span>
            <span class="text-label-md text-on-surface-variant">Alpa</span>
        </div>
    </section>

    {{-- Search & Filter --}}
    <section class="space-y-sm">
        <div class="flex gap-sm">
            <div class="flex-1 relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
                <input type="text" placeholder="Cari nama siswa..."
                       class="w-full pl-10 pr-4 py-3 bg-surface-container-lowest border border-outline-variant
                              rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none text-body-sm"
                       x-model="searchQuery">
            </div>
        </div>
        <div class="flex items-center gap-2 overflow-x-auto hide-scrollbar py-1">
            @foreach(['semua','RPL','Broadcast','hadir','izin','alpa'] as $f)
            <button @click="activeFilter = '{{ $f }}'"
                    :class="activeFilter === '{{ $f }}' ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant'"
                    class="px-md py-1.5 rounded-full text-label-md whitespace-nowrap transition-colors capitalize">
                {{ $f === 'semua' ? 'Semua' : ucfirst($f) }}
            </button>
            @endforeach
        </div>
    </section>

    {{-- Daftar Siswa Magang Hari Ini --}}
    <section class="space-y-md">
        <div class="flex justify-between items-end">
            <h2 class="text-headline-md font-semibold text-on-surface">Absensi Hari Ini</h2>
            <a href="{{ route('reports.index') }}" class="text-primary text-label-md hover:underline">Lihat Laporan</a>
        </div>

        <div class="space-y-sm">
            @forelse($todaySiswa as $siswa)
            @php
                $att         = $siswa->todayAttendance();
                $status      = $att?->status ?? 'alpa';
                $statusClass = match($status) {
                    'hadir'     => 'bg-primary-container/20 text-primary',
                    'terlambat' => 'bg-secondary-container text-on-secondary-container',
                    'izin'      => 'bg-surface-container text-outline',
                    default     => 'bg-error-container/20 text-error',
                };
                $statusLabel = match($status) {
                    'hadir'     => 'Hadir',
                    'terlambat' => 'Terlambat',
                    'izin'      => 'Izin',
                    default     => 'Alpa',
                };
            @endphp
            <div class="bg-surface-container-lowest p-md rounded-xl border border-outline-variant shadow-sm
                        flex items-center justify-between gap-3"
                 x-show="showSiswa('{{ $siswa->jurusan }}', '{{ $status }}', '{{ strtolower($siswa->name) }}')">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-full overflow-hidden bg-surface-container-high shrink-0">
                        <img src="{{ $siswa->photo_url }}" alt="{{ $siswa->name }}"
                             class="w-full h-full object-cover">
                    </div>
                    <div class="min-w-0">
                        <p class="text-body-lg font-semibold text-on-surface truncate">{{ $siswa->name }}</p>
                        <p class="text-body-sm text-on-surface-variant truncate">{{ $siswa->jurusan ?? '—' }}</p>
                        <p class="text-label-md text-outline">
                            Masuk: {{ $att?->jam_masuk ? \Carbon\Carbon::parse($att->jam_masuk)->format('H:i') : '--:--' }}
                            &nbsp;·&nbsp;
                            Pulang: {{ $att?->jam_pulang ? \Carbon\Carbon::parse($att->jam_pulang)->format('H:i') : '--:--' }}
                        </p>
                    </div>
                </div>
                <span class="text-status-badge font-bold px-sm py-1 rounded-full whitespace-nowrap {{ $statusClass }}">
                    {{ $statusLabel }}
                </span>
            </div>
            @empty
            <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant
                        text-center text-on-surface-variant text-body-sm">
                Belum ada siswa magang terdaftar.
            </div>
            @endforelse
        </div>
    </section>

    {{-- Persetujuan Izin --}}
    <section class="space-y-md">
        <div class="flex justify-between items-end">
            <h2 class="text-headline-md font-semibold text-on-surface">
                Persetujuan Izin
                @if($pendingPermissions->count())
                    <span class="ml-sm text-status-badge font-bold px-sm py-0.5 rounded-full
                                 bg-secondary-container text-on-secondary-container">
                        {{ $pendingPermissions->count() }}
                    </span>
                @endif
            </h2>
            <a href="{{ route('permissions.approvals') }}" class="text-primary text-label-md hover:underline">
                Lihat Semua
            </a>
        </div>

        @forelse($pendingPermissions as $perm)
        <div class="bg-surface-container-lowest p-md rounded-xl border border-outline-variant shadow-sm
                    flex items-center gap-3 cursor-pointer hover:bg-surface-container-low transition-colors
                    active:scale-[0.99] duration-100"
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
            {{-- Avatar --}}
            <div class="w-12 h-12 rounded-xl overflow-hidden bg-surface-container-high shrink-0">
                <img src="{{ $perm->user->photo_url }}" alt="{{ $perm->user->name }}"
                     class="w-full h-full object-cover">
            </div>
            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <p class="text-body-lg font-semibold text-on-surface truncate">{{ $perm->user->name }}</p>
                <p class="text-body-sm text-on-surface-variant">
                    {{ $perm->jenis_label }}
                    @if($perm->user->jurusan) · {{ $perm->user->jurusan }} @endif
                </p>
                <p class="text-label-md text-outline">{{ $perm->tanggal->translatedFormat('d M Y') }}</p>
            </div>
            {{-- Chevron --}}
            <span class="material-symbols-outlined text-outline shrink-0">chevron_right</span>
        </div>
        @empty
        <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant
                    text-center text-on-surface-variant text-body-sm">
            Tidak ada pengajuan izin yang menunggu.
        </div>
        @endforelse
    </section>

    {{-- ── Modal Detail Izin ─────────────────────────────────────────────────── --}}
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
             x-transition:enter-start="translate-y-full md:translate-y-0 md:scale-95 opacity-0"
             x-transition:enter-end="translate-y-0 md:scale-100 opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="translate-y-0 md:scale-100 opacity-100"
             x-transition:leave-end="translate-y-full md:translate-y-0 md:scale-95 opacity-0"
             class="bg-surface w-full md:max-w-md rounded-t-2xl md:rounded-xl shadow-xl overflow-hidden">

            {{-- Handle bar (mobile) --}}
            <div class="flex justify-center pt-sm pb-xs md:hidden">
                <div class="w-10 h-1 bg-outline-variant rounded-full"></div>
            </div>

            {{-- Header modal --}}
            <div class="flex items-center justify-between px-md pt-md pb-sm border-b border-outline-variant">
                <p class="text-headline-md font-semibold text-on-surface">Detail Pengajuan Izin</p>
                <button @click="closeModal()"
                        class="material-symbols-outlined text-outline hover:text-on-surface
                               p-1 rounded-full hover:bg-surface-container transition-colors">
                    close
                </button>
            </div>

            {{-- Body modal --}}
            <div class="px-md py-lg space-y-md overflow-y-auto max-h-[70vh]">

                {{-- Profil siswa --}}
                <div class="flex items-center gap-md">
                    <img :src="modal.avatar" :alt="modal.nama"
                         class="w-14 h-14 rounded-full object-cover border-2 border-primary-container">
                    <div>
                        <p class="text-body-lg font-semibold text-on-surface" x-text="modal.nama"></p>
                        <p class="text-body-sm text-on-surface-variant" x-text="modal.jurusan"></p>
                    </div>
                </div>

                {{-- Detail rows --}}
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

                {{-- Alasan --}}
                <div class="space-y-xs">
                    <p class="text-label-md text-on-surface-variant uppercase tracking-wider">Alasan</p>
                    <div class="bg-surface-container-low rounded-xl px-md py-sm">
                        <p class="text-body-sm text-on-surface leading-relaxed" x-text="modal.alasan"></p>
                    </div>
                </div>

                {{-- Lampiran --}}
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

                {{-- Catatan approver (input) --}}
                <div class="space-y-xs" x-show="modal.status === 'menunggu'">
                    <p class="text-label-md text-on-surface-variant uppercase tracking-wider">Catatan (opsional)</p>
                    <textarea x-model="catatan" rows="3"
                              placeholder="Tambahkan catatan untuk siswa..."
                              class="w-full p-md rounded-xl border border-outline-variant bg-surface
                                     focus:border-primary focus:ring-1 focus:ring-primary
                                     text-body-sm resize-none outline-none"></textarea>
                </div>

                {{-- Status badge (sudah diproses) --}}
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

            {{-- Footer modal — tombol approve/tolak --}}
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
                        <span class="material-symbols-outlined text-[18px]"
                              x-show="!processing">check</span>
                        <span class="material-symbols-outlined animate-spin text-[18px]"
                              x-show="processing">progress_activity</span>
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

        // ── Filter logic ───────────────────────────────────────────
        showSiswa(jurusan, status, nama) {
            const q = this.searchQuery.toLowerCase();
            if (q && !nama.includes(q)) return false;
            if (this.activeFilter === 'semua')      return true;
            if (this.activeFilter === 'RPL')        return jurusan === 'RPL';
            if (this.activeFilter === 'Broadcast')  return jurusan === 'Broadcast';
            // status filters
            if (this.activeFilter === 'hadir') return status === 'hadir' || status === 'terlambat';
            if (this.activeFilter === 'izin')  return status === 'izin';
            if (this.activeFilter === 'alpa')  return status === 'alpa';
            return true;
        },

        // ── Modal ──────────────────────────────────────────────────
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
