@extends('layouts.admin')
@section('title', 'Kelola Guru Pembimbing')

@section('content')
<div class="max-w-6xl mx-auto p-4 sm:p-6 space-y-6"
     x-data="{
        showModal: false,
        editMode: false,
        form: {
            id: '', name: '', email: '', nis_nip: '', asal_sekolah: '',
            phone: '', role: 'guru', password: ''
        },
        openEdit(data) {
            this.editMode = true;
            this.form = data;
            this.showModal = true;
        }
     }">

    {{-- ════════════════════════════════════════════════════════════
         HERO HEADER
    ════════════════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden shadow-lg"
             style="background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 55%, #3B82F6 100%); border-radius: 24px; padding: 28px 24px;">
        <div class="pointer-events-none absolute inset-0 overflow-hidden" style="border-radius: 24px;">
            <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full" style="background:rgba(255,255,255,0.08); filter: blur(20px);"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full" style="background:rgba(255,255,255,0.06); filter: blur(16px);"></div>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <p class="text-white/80 text-[12px] font-bold uppercase tracking-wider mb-1">Data Master → Guru</p>
                <h1 class="text-white font-extrabold text-[22px] sm:text-[26px] leading-tight tracking-tight">
                    Guru Pembimbing
                </h1>
                <p class="text-white/80 text-[13px] font-medium mt-1">
                    Kelola daftar guru yang berhak memantau & menyetujui izin siswa.
                </p>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════
         STATISTIK CARDS
    ════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-md">
        {{-- Total Guru --}}
        <div class="bg-white rounded-2xl border border-outline-variant/60 shadow-sm p-md flex items-center gap-md">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: rgba(133,83,0,0.10)">
                <span class="material-symbols-outlined text-[26px] text-secondary" style="font-variation-settings:'FILL' 1">school</span>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Total Guru</p>
                <p class="text-[28px] font-black text-secondary leading-none" style="letter-spacing: -0.03em;">
                    {{ $totalGuru }}
                </p>
            </div>
        </div>

        {{-- Punya NIP --}}
        <div class="bg-white rounded-2xl border border-outline-variant/60 shadow-sm p-md flex items-center gap-md">
            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[26px] text-primary" style="font-variation-settings:'FILL' 1">badge</span>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Punya NIP</p>
                <p class="text-[28px] font-black text-primary leading-none" style="letter-spacing: -0.03em;">
                    {{ $withNip }}
                </p>
            </div>
        </div>

        {{-- Punya Sekolah --}}
        <div class="bg-white rounded-2xl border border-outline-variant/60 shadow-sm p-md flex items-center gap-md">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: rgba(99,102,241,0.10)">
                <span class="material-symbols-outlined text-[26px] text-indigo-600" style="font-variation-settings:'FILL' 1">domain</span>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Instansi</p>
                <p class="text-[28px] font-black text-indigo-600 leading-none" style="letter-spacing: -0.03em;">
                    {{ $instansiCount }}
                </p>
            </div>
        </div>

        {{-- Punya Telepon --}}
        <div class="bg-white rounded-2xl border border-outline-variant/60 shadow-sm p-md flex items-center gap-md">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: rgba(16,185,129,0.12)">
                <span class="material-symbols-outlined text-[26px] text-emerald-600" style="font-variation-settings:'FILL' 1">phone</span>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Ada Kontak</p>
                <p class="text-[28px] font-black text-emerald-600 leading-none" style="letter-spacing: -0.03em;">
                    {{ $withPhone }}
                </p>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════
         FLASH NOTIFICATIONS
    ════════════════════════════════════════════════════════════ --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-md py-sm rounded-xl flex items-center gap-sm shadow-sm">
            <span class="material-symbols-outlined text-[20px] text-emerald-600" style="font-variation-settings:'FILL' 1">check_circle</span>
            <span class="text-[13px] font-semibold">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-md py-sm rounded-xl flex items-center gap-sm shadow-sm">
            <span class="material-symbols-outlined text-[20px] text-red-600" style="font-variation-settings:'FILL' 1">error</span>
            <span class="text-[13px] font-semibold">{{ session('error') }}</span>
        </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════
         SEARCH & FILTER BAR
    ════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-outline-variant/60 shadow-sm p-md">
        <form method="GET" action="{{ route('admin.guru.index') }}" class="flex gap-sm">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama, email, NIP, atau instansi..."
                       class="w-full pl-xl pr-md py-sm bg-surface-container-low border border-outline-variant rounded-xl text-[13px] focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
            </div>
            <button type="submit"
                    class="hover:opacity-90 text-white font-semibold px-md py-sm rounded-xl transition-all active:scale-95 flex items-center gap-xs whitespace-nowrap"
                    style="background: #855300;">
                <span class="material-symbols-outlined text-[18px]">search</span>
                <span class="hidden sm:inline">Cari</span>
            </button>
            @if(request('search'))
                <a href="{{ route('admin.guru.index') }}"
                   class="bg-surface-container-high hover:bg-surface-container text-on-surface-variant font-semibold px-md py-sm rounded-xl flex items-center gap-xs transition-all whitespace-nowrap">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                    <span class="hidden sm:inline">Reset</span>
                </a>
            @endif
        </form>
    </div>

    {{-- ════════════════════════════════════════════════════════════
         DATA TABLE
    ════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-outline-variant/60 shadow-sm overflow-hidden">

        {{-- Table header bar --}}
        <div class="px-lg py-md border-b border-outline-variant/50 flex items-center justify-between">
            <div class="flex items-center gap-sm">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: rgba(133,83,0,0.10)">
                    <span class="material-symbols-outlined text-[18px] text-secondary" style="font-variation-settings:'FILL' 1">school</span>
                </div>
                <div>
                    <p class="text-[14px] font-bold text-on-surface">Daftar Guru Pembimbing</p>
                    <p class="text-[11px] text-on-surface-variant">
                        {{ $gurus->total() }} guru terdaftar
                        @if(request('search')) — hasil pencarian "{{ request('search') }}" @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low/70 border-b border-outline-variant/50">
                    <tr>
                        <th class="py-sm px-lg text-[11px] font-bold uppercase tracking-widest text-on-surface-variant">Guru Pembimbing</th>
                        <th class="py-sm px-md text-[11px] font-bold uppercase tracking-widest text-on-surface-variant">NIP / NUPTK</th>
                        <th class="py-sm px-md text-[11px] font-bold uppercase tracking-widest text-on-surface-variant">Instansi</th>
                        <th class="py-sm px-md text-[11px] font-bold uppercase tracking-widest text-on-surface-variant">Telepon</th>
                        <th class="py-sm px-md text-[11px] font-bold uppercase tracking-widest text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @forelse($gurus as $guru)
                        <tr class="hover:bg-secondary/[0.02] transition-colors group">
                            {{-- Avatar + Nama --}}
                            <td class="py-md px-lg">
                                <div class="flex items-center gap-md">
                                    <div class="relative shrink-0">
                                        <img src="{{ $guru->photo_url }}"
                                             alt="{{ $guru->name }}"
                                             class="w-10 h-10 rounded-full object-cover ring-2 ring-outline-variant/30">
                                        <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-secondary border-2 border-white"
                                              title="Guru Pembimbing"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[13px] font-bold text-on-surface truncate">{{ $guru->name }}</p>
                                        <p class="text-[12px] text-on-surface-variant truncate">{{ $guru->email }}</p>
                                    </div>
                                </div>
                            </td>
                            {{-- NIP --}}
                            <td class="py-md px-md">
                                @if($guru->nis_nip)
                                    <span class="inline-flex items-center gap-1 text-[12px] font-bold text-secondary bg-secondary/10 px-2 py-0.5 rounded-lg">
                                        <span class="material-symbols-outlined text-[13px]">badge</span>
                                        {{ $guru->nis_nip }}
                                    </span>
                                @else
                                    <span class="text-[12px] text-outline italic">—</span>
                                @endif
                            </td>
                            {{-- Instansi --}}
                            <td class="py-md px-md">
                                @if($guru->asal_sekolah)
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[14px] text-outline">domain</span>
                                        <p class="text-[13px] font-semibold text-on-surface max-w-[180px] truncate">{{ $guru->asal_sekolah }}</p>
                                    </div>
                                @else
                                    <span class="text-[12px] text-outline italic">—</span>
                                @endif
                            </td>
                            {{-- Telepon --}}
                            <td class="py-md px-md">
                                @if($guru->phone)
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[14px] text-outline">phone</span>
                                        <p class="text-[13px] text-on-surface-variant">{{ $guru->phone }}</p>
                                    </div>
                                @else
                                    <span class="text-[12px] text-outline italic">—</span>
                                @endif
                            </td>
                            {{-- Aksi --}}
                            <td class="py-md px-md text-right">
                                <div class="flex items-center justify-end gap-xs opacity-60 group-hover:opacity-100 transition-opacity">
                                    <button @click="openEdit({
                                                id: '{{ $guru->id }}',
                                                name: '{{ addslashes($guru->name) }}',
                                                email: '{{ addslashes($guru->email) }}',
                                                nis_nip: '{{ $guru->nis_nip }}',
                                                asal_sekolah: '{{ addslashes($guru->asal_sekolah) }}',
                                                phone: '{{ $guru->phone }}',
                                                role: 'guru',
                                                password: ''
                                            })"
                                            class="w-9 h-9 rounded-xl flex items-center justify-center text-secondary bg-secondary/5 hover:bg-secondary/15 transition-all active:scale-90"
                                            title="Edit data guru">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <form method="POST" action="{{ route('admin.users.destroy', $guru) }}"
                                          onsubmit="return confirm('Hapus guru {{ addslashes($guru->name) }}? Tindakan ini tidak dapat dibatalkan!')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-9 h-9 rounded-xl flex items-center justify-center text-red-600 bg-red-50 hover:bg-red-100 transition-all active:scale-90"
                                                title="Hapus guru">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-2xl text-center">
                                <div class="flex flex-col items-center gap-md">
                                    <div class="w-16 h-16 rounded-2xl bg-surface-container flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[36px] text-outline">person_search</span>
                                    </div>
                                    <div>
                                        <p class="text-[14px] font-bold text-on-surface mb-1">
                                            @if(request('search'))
                                                Guru tidak ditemukan
                                            @else
                                                Belum ada data guru pembimbing
                                            @endif
                                        </p>
                                        @if(request('search'))
                                            <p class="text-[12px] text-on-surface-variant">
                                                Coba ubah kata kunci pencarian.
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($gurus->hasPages())
            <div class="px-lg py-md border-t border-outline-variant/50 bg-surface-container-low/40">
                {{ $gurus->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    {{-- ════════════════════════════════════════════════════════════
         MODAL FORM (EDIT)
    ════════════════════════════════════════════════════════════ --}}
    <div x-show="showModal"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-md bg-black/50 backdrop-blur-sm">

        <div @click.away="showModal = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="bg-white rounded-2xl border border-outline-variant/60 max-w-xl w-full shadow-2xl max-h-[90vh] overflow-y-auto">

            {{-- Modal Header --}}
            <div class="px-lg py-md border-b border-outline-variant/50 flex items-center justify-between sticky top-0 bg-white z-10">
                <div class="flex items-center gap-sm">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: rgba(133,83,0,0.10)">
                        <span class="material-symbols-outlined text-[18px] text-secondary" style="font-variation-settings:'FILL' 1">edit</span>
                    </div>
                    <div>
                        <h3 class="text-[15px] font-extrabold text-on-surface">Edit Data Guru</h3>
                        <p class="text-[11px] text-on-surface-variant">Perbarui informasi guru pembimbing</p>
                    </div>
                </div>
                <button @click="showModal = false"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-outline hover:text-on-surface hover:bg-surface-container transition-all">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form :action="'/admin/users/' + form.id"
                  method="POST"
                  class="p-lg space-y-md">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <input type="hidden" name="role" value="guru">

                {{-- Nama --}}
                <div>
                    <label class="block text-[12px] font-bold text-on-surface mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" x-model="form.name" required
                           placeholder="Contoh: Dra. Siti Aminah, M.Pd."
                           class="w-full px-md py-sm bg-surface-container-low border border-outline-variant rounded-xl text-[13px] focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                </div>

                {{-- Email + NIP --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface mb-1.5">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" x-model="form.email" required
                               placeholder="guru@sekolah.sch.id"
                               class="w-full px-md py-sm bg-surface-container-low border border-outline-variant rounded-xl text-[13px] focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface mb-1.5">NIP / NUPTK</label>
                        <input type="text" name="nis_nip" x-model="form.nis_nip"
                               placeholder="Nomor Induk Pegawai"
                               class="w-full px-md py-sm bg-surface-container-low border border-outline-variant rounded-xl text-[13px] focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                    </div>
                </div>

                {{-- Instansi + Telepon --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface mb-1.5">Asal Sekolah / Instansi</label>
                        <input type="text" name="asal_sekolah" x-model="form.asal_sekolah"
                               placeholder="SMKN 1 Surabaya"
                               class="w-full px-md py-sm bg-surface-container-low border border-outline-variant rounded-xl text-[13px] focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface mb-1.5">Nomor Telepon</label>
                        <input type="text" name="phone" x-model="form.phone"
                               placeholder="081234567890"
                               class="w-full px-md py-sm bg-surface-container-low border border-outline-variant rounded-xl text-[13px] focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-[12px] font-bold text-on-surface mb-1.5">
                        Password
                        <span x-show="editMode" class="text-outline font-normal">(Kosongkan jika tidak diubah)</span>
                    </label>
                    <input type="password" name="password" x-model="form.password"
                           :placeholder="editMode ? '••••••••' : 'Default: password'"
                           class="w-full px-md py-sm bg-surface-container-low border border-outline-variant rounded-xl text-[13px] focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                </div>

                {{-- Info Note --}}
                <div class="flex items-start gap-sm bg-amber-50 border border-amber-200 rounded-xl px-md py-sm">
                    <span class="material-symbols-outlined text-[16px] text-amber-600 mt-0.5 shrink-0" style="font-variation-settings:'FILL' 1">info</span>
                    <p class="text-[11px] text-amber-800 leading-relaxed">
                        Guru pembimbing memiliki akses untuk melihat rekap absensi semua siswa dan menyetujui/menolak pengajuan izin.
                    </p>
                </div>

                {{-- Form Actions --}}
                <div class="flex justify-end gap-sm pt-sm border-t border-outline-variant/50">
                    <button type="button" @click="showModal = false"
                            class="px-md py-sm bg-surface-container text-on-surface-variant font-semibold rounded-xl hover:bg-surface-container-high transition-all active:scale-95">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-md py-sm text-white font-bold rounded-xl shadow-md transition-all active:scale-95 flex items-center gap-xs hover:opacity-90"
                            style="background: #855300;">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
