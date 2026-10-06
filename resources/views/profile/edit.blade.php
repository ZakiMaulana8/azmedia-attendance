@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div class="max-w-6xl mx-auto p-4 sm:p-6 space-y-6">

    {{-- ══════════════════════════════════════════════════════════
         HERO HEADER CARD
    ══════════════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden shadow-lg text-white"
             style="background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 55%, #3B82F6 100%); border-radius: 24px; padding: 28px 24px;">
        {{-- Decorative background blur elements --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden" style="border-radius: 24px;">
            <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full" style="background:rgba(255,255,255,0.08); filter: blur(20px);"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full" style="background:rgba(255,255,255,0.06); filter: blur(16px);"></div>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-md"
                     style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35); backdrop-filter: blur(8px);">
                    <span class="material-symbols-outlined text-white text-[26px]" style="font-variation-settings:'FILL' 1">account_circle</span>
                </div>
                <div>
                    <h1 class="text-white font-extrabold text-[22px] sm:text-[24px] leading-tight tracking-tight">
                        Profil Saya
                    </h1>
                    @php
                        $profileSubtitle = match($user->role) {
                            'admin'    => 'Informasi akun dan hak akses administrator',
                            'guru'     => 'Informasi akun dan profil guru pembimbing',
                            'karyawan' => 'Informasi akun dan profil karyawan',
                            default    => 'Informasi akun dan data magang',
                        };
                    @endphp
                    <p class="text-white/80 text-[13px] font-medium mt-0.5">{{ $profileSubtitle }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Flash status notification --}}
    @if(session('status'))
    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-3 shadow-sm">
        <span class="material-symbols-outlined text-emerald-600 text-[20px] flex-shrink-0"
              style="font-variation-settings:'FILL' 1">check_circle</span>
        <p class="text-[13px] text-emerald-800 font-bold">{{ session('status') }}</p>
    </div>
    @endif

    {{-- ── Responsive Grid Layout (Desktop: 2 Kolom, Mobile/Tablet: 1 Kolom) ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- ════════════════════════════════════════════════════════
             KOLOM KIRI: Identitas & Status Sesi
        ════════════════════════════════════════════════════════ --}}
        <div class="lg:col-span-4 space-y-6">

            {{-- Avatar & Identitas Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex flex-col items-center text-center gap-4 relative overflow-hidden">
                <div class="relative mt-2">
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full overflow-hidden border-4 border-blue-100 shadow-md">
                        <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute bottom-1 right-1 w-7 h-7 rounded-full bg-[#2563EB] flex items-center justify-center border-2 border-white text-white shadow-sm" title="Verified Account">
                        <span class="material-symbols-outlined text-[14px]" style="font-variation-settings:'FILL' 1">verified</span>
                    </div>
                </div>

                <div class="w-full">
                    <h3 class="text-[18px] font-extrabold text-slate-900 leading-tight truncate px-2" title="{{ $user->name }}">
                        {{ $user->name }}
                    </h3>
                    <p class="text-[12px] sm:text-[13px] text-slate-500 font-medium mt-1 break-all px-2">
                        {{ $user->email }}
                    </p>
                </div>

                {{-- Role badge dinamis --}}
                @php
                    $roleBadge = match($user->role) {
                        'admin'    => ['label' => 'Administrator',    'class' => 'bg-purple-100 text-purple-900 border-purple-200', 'icon' => 'admin_panel_settings'],
                        'guru'     => ['label' => 'Guru Pembimbing', 'class' => 'bg-blue-100 text-[#1D4ED8] border-blue-200', 'icon' => 'supervisor_account'],
                        'karyawan' => ['label' => 'Karyawan',        'class' => 'bg-blue-100 text-[#1E40AF] border-blue-200', 'icon' => 'badge'],
                        default    => ['label' => 'Siswa Magang',    'class' => 'bg-blue-100 text-[#2563EB] border-blue-200', 'icon' => 'school'],
                    };
                @endphp
                <span class="inline-flex items-center gap-1.5 text-[12px] font-extrabold px-4 py-1.5 rounded-full border {{ $roleBadge['class'] }}">
                    <span class="material-symbols-outlined text-[16px]">{{ $roleBadge['icon'] }}</span>
                    {{ $roleBadge['label'] }}
                </span>

                <div class="flex items-center gap-1.5 text-[12px] text-slate-500 font-semibold w-full justify-center"
                     style="padding-top:16px; border-top:1px solid #f1f5f9;">
                    <span class="material-symbols-outlined" style="font-size:16px; line-height:1;">verified_user</span>
                    <span>Terhubung dengan Google</span>
                </div>
            </div>

            {{-- Logout Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h4 class="text-[14px] font-bold text-slate-900">Keluar Sesi</h4>
                        <p class="text-[12px] text-slate-500">Log out dari akun ini</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                        @csrf
                        <button type="submit"
                                class="h-10 px-4 border border-red-200 text-red-600 rounded-xl font-bold text-[13px]
                                       flex items-center gap-1.5 hover:bg-red-50 active:scale-95 transition-all">
                            <span class="material-symbols-outlined text-[18px]">logout</span>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

        </div>

        {{-- ════════════════════════════════════════════════════════
             KOLOM KANAN: Form Edit Profil & Info Role
        ════════════════════════════════════════════════════════ --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- Form Edit Profil --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-200/80 bg-slate-50">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#2563EB] border border-blue-100 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">manage_accounts</span>
                    </div>
                    <div>
                        <p class="text-[15px] font-extrabold text-slate-900">Ubah Informasi Profil</p>
                        <p class="text-[12px] text-slate-500">Perbarui nama dan nomor kontak Anda</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-6">
                        {{-- Nama Lengkap --}}
                        <div>
                            <label for="name" class="block text-[12px] font-extrabold uppercase tracking-wider text-slate-600 mb-2">
                                Nama Lengkap
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute text-slate-400 pointer-events-none"
                                      style="left:16px; top:50%; transform:translateY(-50%); font-size:20px; line-height:1;">person</span>
                                <input id="name" type="text" name="name"
                                       value="{{ old('name', $user->name) }}"
                                       style="padding-left:48px; padding-right:16px;"
                                       class="w-full h-12 rounded-xl border border-slate-300
                                              bg-white focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20
                                              text-[14px] font-bold text-slate-900 transition-all outline-none"
                                       required>
                            </div>
                            @error('name')
                            <p class="mt-1.5 text-[12px] text-red-500 font-semibold flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">error</span>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        {{-- Email (Read-only Full Width) --}}
                        <div>
                            <label class="block text-[12px] font-extrabold uppercase tracking-wider text-slate-600 mb-2">
                                Alamat Email (Akun Google)
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute text-slate-400 pointer-events-none"
                                      style="left:16px; top:50%; transform:translateY(-50%); font-size:20px; line-height:1;">mail</span>
                                <input type="text" value="{{ $user->email }}" disabled
                                       style="padding-left:48px; padding-right:48px;"
                                       class="w-full h-12 rounded-xl border border-slate-200
                                              bg-slate-50 text-slate-700 text-[14px] font-bold cursor-not-allowed select-none">
                                <span class="material-symbols-outlined absolute text-slate-400 pointer-events-none"
                                      style="right:16px; top:50%; transform:translateY(-50%); font-size:18px; line-height:1;">lock</span>
                            </div>
                            <p class="text-[12px] text-slate-400 mt-2 font-medium flex items-start gap-1.5" style="line-height:1.4;">
                                <span class="material-symbols-outlined shrink-0" style="font-size:14px; margin-top:1px;">info</span>
                                <span>Alamat email terhubung otomatis dengan akun Google Anda dan tidak dapat diubah.</span>
                            </p>
                        </div>

                        {{-- Nomor Telepon --}}
                        <div>
                            <label for="phone" class="block text-[12px] font-extrabold uppercase tracking-wider text-slate-600 mb-2">
                                Nomor Telepon / WhatsApp
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute text-slate-400 pointer-events-none"
                                      style="left:16px; top:50%; transform:translateY(-50%); font-size:20px; line-height:1;">call</span>
                                <input id="phone" type="tel" name="phone"
                                       value="{{ old('phone', $user->phone) }}"
                                       placeholder="Contoh: 081234567890"
                                       style="padding-left:48px; padding-right:16px;"
                                       class="w-full h-12 rounded-xl border border-slate-300
                                              bg-white focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20
                                              text-[14px] font-bold text-slate-900 transition-all outline-none">
                            </div>
                            @error('phone')
                            <p class="mt-1.5 text-[12px] text-red-500 font-semibold flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">error</span>
                                {{ $message }}
                            </p>
                            @enderror
                            <p class="text-[12px] text-slate-400 mt-2 font-medium flex items-start gap-1.5" style="line-height:1.4;">
                                <span class="material-symbols-outlined shrink-0" style="font-size:14px; margin-top:1px;">help</span>
                                <span>Masukkan nomor WhatsApp aktif yang dapat dihubungi.</span>
                            </p>
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit"
                                class="w-full sm:w-auto sm:min-w-[180px] h-11 px-6 rounded-xl font-extrabold text-[14px] text-white
                                       flex items-center justify-center gap-2
                                       transition-all duration-200 hover:bg-[#1D4ED8] active:scale-[0.98] shadow-md"
                                style="background: #2563EB;">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- ── Role-Specific Card ──────────────────────────────── --}}

            @if($user->isSiswa())
            {{-- Info Magang Card (Khusus Siswa) --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-200/80">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#2563EB] border border-blue-100 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">work_history</span>
                    </div>
                    <div>
                        <h4 class="text-[15px] font-extrabold text-slate-900">Data Magang</h4>
                        <p class="text-[12px] text-slate-500">Informasi program magang yang aktif</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-[12px]">
                    <div class="bg-slate-50 border border-slate-200/60 p-3 rounded-xl">
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Asal Sekolah</p>
                        <p class="text-slate-900 font-extrabold text-[13px] leading-tight">{{ $user->asal_sekolah ?? '—' }}</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200/60 p-3 rounded-xl">
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Jurusan</p>
                        <p class="text-slate-900 font-extrabold text-[13px] leading-tight">{{ $user->jurusan ?? '—' }}</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200/60 p-3 rounded-xl">
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">NIS</p>
                        <p class="text-slate-900 font-extrabold text-[13px] leading-tight">{{ $user->nis_nip ?? '—' }}</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200/60 p-3 rounded-xl">
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Sisa Hari</p>
                        @php $sisa = $user->sisaHariMagang(); @endphp
                        <p class="font-extrabold text-[13px] leading-tight {{ $sisa !== null && $sisa <= 7 ? 'text-red-600' : 'text-[#2563EB]' }}">
                            {{ $sisa !== null ? $sisa . ' hari' : '—' }}
                        </p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200/60 p-3 rounded-xl">
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Mulai Magang</p>
                        <p class="text-slate-900 font-extrabold text-[13px] leading-tight">
                            {{ $user->tanggal_mulai?->translatedFormat('d M Y') ?? '—' }}
                        </p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200/60 p-3 rounded-xl">
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Selesai Magang</p>
                        <p class="text-slate-900 font-extrabold text-[13px] leading-tight">
                            {{ $user->tanggal_selesai?->translatedFormat('d M Y') ?? '—' }}
                        </p>
                    </div>
                </div>

                @if($user->tanggal_mulai && $user->tanggal_selesai)
                @php $progress = $user->progressMagang(); @endphp
                <div class="pt-3 border-t border-slate-100">
                    <div class="flex justify-between text-[12px] text-slate-600 font-bold mb-1.5">
                        <span>Progress Magang</span>
                        <span class="text-[#2563EB] font-extrabold">{{ $progress }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5">
                        <div class="bg-[#2563EB] h-2.5 rounded-full transition-all duration-700"
                             style="width: {{ $progress }}%"></div>
                    </div>
                </div>
                @endif

                <p class="text-[11px] text-slate-400 font-medium italic">
                    * Data magang hanya dapat diubah oleh administrator.
                </p>
            </div>

            @elseif($user->isGuru())
            {{-- Info Pembimbing Sekolah (Khusus Guru) --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-200/80">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#2563EB] border border-blue-100 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">supervisor_account</span>
                    </div>
                    <div>
                        <h4 class="text-[15px] font-extrabold text-slate-900">Informasi Guru Pembimbing</h4>
                        <p class="text-[12px] text-slate-500">Data sekolah asal dan identitas pembimbing</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-[12px]">
                    <div class="bg-slate-50 border border-slate-200/60 p-4 rounded-xl">
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Asal Sekolah</p>
                        <p class="text-slate-900 font-extrabold text-[14px] leading-tight">{{ $user->asal_sekolah ?? '—' }}</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200/60 p-4 rounded-xl">
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">NIP</p>
                        <p class="text-slate-900 font-extrabold text-[14px] leading-tight">{{ $user->nis_nip ?? '—' }}</p>
                    </div>
                </div>

                <p class="text-[11px] text-slate-400 font-medium italic">
                    * Data sekolah asal dan NIP dapat diperbarui melalui Administrator.
                </p>
            </div>

            @elseif($user->isKaryawan() || $user->isAdmin())
            {{-- Informasi Akun (Karyawan / Administrator) --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-200/80">
                    <div class="w-10 h-10 rounded-xl {{ $user->isAdmin() ? 'bg-purple-50 text-purple-700 border border-purple-100' : 'bg-blue-50 text-[#1E40AF] border border-blue-100' }} flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">{{ $user->isAdmin() ? 'admin_panel_settings' : 'badge' }}</span>
                    </div>
                    <div>
                        <h4 class="text-[15px] font-extrabold text-slate-900">Informasi Akun</h4>
                        <p class="text-[12px] text-slate-500">Detail status akun pengguna saat ini</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-[12px]">
                    <div class="bg-slate-50 border border-slate-200/60 p-4 rounded-xl">
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Peran Akun</p>
                        <p class="text-slate-900 font-extrabold text-[14px] leading-tight">{{ $roleBadge['label'] }}</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200/60 p-4 rounded-xl">
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Terdaftar Sejak</p>
                        <p class="text-slate-900 font-extrabold text-[14px] leading-tight">
                            {{ $user->created_at?->translatedFormat('d F Y') ?? '—' }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

        </div>

    </div>
</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="profile" :role="auth()->user()->role" />
@endsection
