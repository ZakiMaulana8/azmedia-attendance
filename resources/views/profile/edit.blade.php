@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div class="min-h-full">

    {{-- ── Hero Header ─────────────────────────────────────────────── --}}
    <section class="relative overflow-hidden text-white"
             style="background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 55%, #3B82F6 100%); border-radius: 0 0 32px 32px;">
        {{-- Ornamen dekoratif latar belakang --}}
        <div class="pointer-events-none absolute -top-10 -right-10 w-48 h-48 rounded-full"
             style="background: rgba(255,255,255,0.07)"></div>
        <div class="pointer-events-none absolute top-6 -right-2 w-28 h-28 rounded-full"
             style="background: rgba(255,255,255,0.05)"></div>
        <div class="pointer-events-none absolute -bottom-10 left-10 w-36 h-36 rounded-full"
             style="background: rgba(255,255,255,0.03)"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 lg:pt-10 pb-12 sm:pb-14 relative z-10">
            <p class="text-white/60 text-[11px] sm:text-[12px] font-semibold uppercase tracking-widest mb-1">
                Akun & Pengaturan
            </p>
            <h2 class="text-white font-extrabold text-[22px] sm:text-[26px] lg:text-[28px] leading-tight tracking-tight">
                Profil Saya
            </h2>
            @php
                $profileSubtitle = match($user->role) {
                    'admin'    => 'Informasi akun dan hak akses administrator',
                    'guru'     => 'Informasi akun dan profil guru pembimbing',
                    'karyawan' => 'Informasi akun dan profil karyawan',
                    default    => 'Informasi akun dan data magang',
                };
            @endphp
            <p class="text-white/70 text-[13px] sm:text-[14px] mt-1">{{ $profileSubtitle }}</p>
        </div>
    </section>

    {{-- ── Main Container ──────────────────────────────────────────── --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-4 sm:-mt-6 pb-8 md:pb-28 space-y-6 relative z-10">

        {{-- Flash status notification --}}
        @if(session('status'))
        <div class="flex items-center gap-3 bg-[#D1FAE5] border border-[#6EE7B7] rounded-2xl px-4 py-3 shadow-sm">
            <span class="material-symbols-outlined text-[#059669] text-[18px] flex-shrink-0"
                  style="font-variation-settings:'FILL' 1">check_circle</span>
            <p class="text-[13px] text-[#065F46] font-semibold">{{ session('status') }}</p>
        </div>
        @endif

        {{-- ── Responsive Grid Layout (Desktop: 2 Kolom, Mobile/Tablet: 1 Kolom) ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            {{-- ════════════════════════════════════════════════════════
                 KOLOM KIRI (Desktop: 4 Col / Mobile: Full Width)
            ════════════════════════════════════════════════════════ --}}
            <div class="lg:col-span-4 xl:col-span-4 space-y-6">

                {{-- ── Avatar & Identitas Card ────────────────────────── --}}
                <div class="bg-white rounded-2xl border border-outline-variant/50 shadow-sm p-6 flex flex-col items-center text-center gap-3 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-primary/5 rounded-full pointer-events-none"></div>

                    <div class="relative">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full overflow-hidden border-4 border-primary/10 shadow-md">
                            <img src="{{ $user->photo_url }}" alt="{{ $user->name }}"
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="absolute bottom-0 right-0 w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-primary flex items-center justify-center border-2 border-white text-white shadow-sm">
                            <span class="material-symbols-outlined text-[14px] sm:text-[16px]" style="font-variation-settings:'FILL' 1">verified</span>
                        </div>
                    </div>

                    <div class="w-full max-w-full">
                        <h3 class="text-[18px] sm:text-[20px] font-extrabold text-on-surface leading-tight truncate px-2" title="{{ $user->name }}">
                            {{ $user->name }}
                        </h3>
                        <p class="text-[12px] sm:text-[13px] text-on-surface-variant font-medium mt-1 break-all px-2">
                            {{ $user->email }}
                        </p>
                    </div>

                    {{-- Role badge dinamis --}}
                    @php
                        $roleBadge = match($user->role) {
                            'admin'    => ['label' => 'Administrator',    'class' => 'bg-[#EDE9FE] text-[#5B21B6]', 'icon' => 'admin_panel_settings'],
                            'guru'     => ['label' => 'Guru Pembimbing', 'class' => 'bg-[#EFF6FF] text-[#1D4ED8]', 'icon' => 'supervisor_account'],
                            'karyawan' => ['label' => 'Karyawan',        'class' => 'bg-[#DBEAFE] text-[#1E40AF]', 'icon' => 'badge'],
                            default    => ['label' => 'Siswa Magang',    'class' => 'bg-[#DBEAFE] text-[#2563EB]', 'icon' => 'school'],
                        };
                    @endphp
                    <span class="inline-flex items-center gap-1.5 text-[11px] sm:text-[12px] font-extrabold px-3.5 py-1.5 rounded-full {{ $roleBadge['class'] }}">
                        <span class="material-symbols-outlined text-[15px]">{{ $roleBadge['icon'] }}</span>
                        {{ $roleBadge['label'] }}
                    </span>

                    <div class="flex items-center gap-1.5 text-[11px] text-outline font-semibold pt-2 border-t border-outline-variant/30 w-full justify-center">
                        <span class="material-symbols-outlined text-[15px]">login</span>
                        Login via Google
                    </div>
                </div>

                {{-- ── Logout Card (Desktop view) ─────────────────────── --}}
                <div class="hidden lg:block bg-white rounded-2xl border border-outline-variant/50 shadow-sm p-5">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h4 class="text-[14px] font-bold text-on-surface">Keluar Sesi</h4>
                            <p class="text-[12px] text-on-surface-variant">Log out dari aplikasi ini</p>
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
                 KOLOM KANAN (Desktop: 8 Col / Mobile: Full Width)
            ════════════════════════════════════════════════════════ --}}
            <div class="lg:col-span-8 xl:col-span-8 space-y-6">

                {{-- ── Edit Form Card (Nama & Telepon) ────────────────── --}}
                <div class="bg-white rounded-2xl border border-outline-variant/50 shadow-sm overflow-hidden">
                    <div class="flex items-center gap-3 px-5 py-4 border-b border-outline-variant/40 bg-surface-container/10">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary text-[20px]"
                                  style="font-variation-settings:'FILL' 1">manage_accounts</span>
                        </div>
                        <div>
                            <p class="text-[14px] sm:text-[15px] font-bold text-on-surface">Ubah Informasi Profil</p>
                            <p class="text-[11px] sm:text-[12px] text-on-surface-variant">Update nama dan nomor kontak Anda</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}" class="p-5 sm:p-6 space-y-5">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                            {{-- Nama Lengkap --}}
                            <div class="sm:col-span-2">
                                <label for="name" class="block text-[12px] font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                                    Nama Lengkap
                                </label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">
                                        person
                                    </span>
                                    <input id="name" type="text" name="name"
                                           value="{{ old('name', $user->name) }}"
                                           class="w-full h-touch-target pl-10 pr-4 rounded-xl border border-outline-variant/60
                                                  bg-surface-container/30 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20
                                                  text-[14px] font-semibold text-on-surface transition-all duration-150 outline-none"
                                           required>
                                </div>
                                @error('name')
                                <p class="mt-1.5 text-[12px] text-red-500 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">error</span>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            {{-- Email (Read-only) --}}
                            <div class="sm:col-span-1">
                                <label class="block text-[12px] font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                                    Email
                                </label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">
                                        mail
                                    </span>
                                    <input type="text" value="{{ $user->email }}" disabled
                                           class="w-full h-touch-target pl-10 pr-10 rounded-xl border border-outline-variant/40
                                                  bg-surface-container text-on-surface-variant/70 text-[13px] sm:text-[14px] font-semibold cursor-not-allowed truncate">
                                    <span class="material-symbols-outlined absolute right-3.5 top-1/2 -translate-y-1/2 text-outline text-[18px]">lock</span>
                                </div>
                                <p class="text-[11px] text-outline mt-1.5">Email dikelola Google, tidak dapat diubah.</p>
                            </div>

                            {{-- Nomor Telepon --}}
                            <div class="sm:col-span-1">
                                <label for="phone" class="block text-[12px] font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                                    Nomor Telepon
                                </label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">
                                        call
                                    </span>
                                    <input id="phone" type="tel" name="phone"
                                           value="{{ old('phone', $user->phone) }}"
                                           placeholder="Contoh: 081234567890"
                                           class="w-full h-touch-target pl-10 pr-4 rounded-xl border border-outline-variant/60
                                                  bg-surface-container/30 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20
                                                  text-[14px] font-semibold text-on-surface transition-all duration-150 outline-none">
                                </div>
                                @error('phone')
                                <p class="mt-1.5 text-[12px] text-red-500 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">error</span>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit"
                                    class="w-full sm:w-auto sm:min-w-[180px] h-touch-target px-6 rounded-2xl font-extrabold text-[14px] text-white
                                           flex items-center justify-center gap-2
                                           transition-all duration-200 hover:bg-[#1D4ED8] active:scale-[0.98]"
                                    style="background: #2563EB; box-shadow: 0 4px 16px rgba(37,99,235,0.30);">
                                <span class="material-symbols-outlined text-[18px]">save</span>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ── Role-Specific Card ──────────────────────────────── --}}

                @if($user->isSiswa())
                {{-- Info Magang Card (Khusus Siswa) --}}
                <div class="bg-white rounded-2xl border border-outline-variant/50 shadow-sm p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-3 pb-3 border-b border-outline-variant/40">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary text-[18px]"
                                  style="font-variation-settings:'FILL' 1">work_history</span>
                        </div>
                        <div>
                            <h4 class="text-[14px] sm:text-[15px] font-bold text-on-surface">Data Magang</h4>
                            <p class="text-[11px] sm:text-[12px] text-on-surface-variant">Informasi program magang yang aktif</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-[12px]">
                        <div class="bg-surface-container/40 p-3 rounded-xl">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-1">Asal Sekolah</p>
                            <p class="text-on-surface font-bold text-[13px] leading-tight">{{ $user->asal_sekolah ?? '—' }}</p>
                        </div>
                        <div class="bg-surface-container/40 p-3 rounded-xl">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-1">Jurusan</p>
                            <p class="text-on-surface font-bold text-[13px] leading-tight">{{ $user->jurusan ?? '—' }}</p>
                        </div>
                        <div class="bg-surface-container/40 p-3 rounded-xl">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-1">NIS</p>
                            <p class="text-on-surface font-bold text-[13px] leading-tight">{{ $user->nis_nip ?? '—' }}</p>
                        </div>
                        <div class="bg-surface-container/40 p-3 rounded-xl">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-1">Sisa Hari</p>
                            @php $sisa = $user->sisaHariMagang(); @endphp
                            <p class="font-bold text-[13px] leading-tight {{ $sisa !== null && $sisa <= 7 ? 'text-red-600' : 'text-primary' }}">
                                {{ $sisa !== null ? $sisa . ' hari' : '—' }}
                            </p>
                        </div>
                        <div class="bg-surface-container/40 p-3 rounded-xl">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-1">Mulai Magang</p>
                            <p class="text-on-surface font-bold text-[13px] leading-tight">
                                {{ $user->tanggal_mulai?->translatedFormat('d M Y') ?? '—' }}
                            </p>
                        </div>
                        <div class="bg-surface-container/40 p-3 rounded-xl">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-1">Selesai Magang</p>
                            <p class="text-on-surface font-bold text-[13px] leading-tight">
                                {{ $user->tanggal_selesai?->translatedFormat('d M Y') ?? '—' }}
                            </p>
                        </div>
                    </div>

                    @if($user->tanggal_mulai && $user->tanggal_selesai)
                    @php $progress = $user->progressMagang(); @endphp
                    <div class="pt-3 border-t border-outline-variant/30">
                        <div class="flex justify-between text-[11px] sm:text-[12px] text-on-surface-variant font-bold mb-1.5">
                            <span>Progress Magang</span>
                            <span class="text-primary">{{ $progress }}%</span>
                        </div>
                        <div class="w-full bg-surface-container rounded-full h-2.5">
                            <div class="bg-primary h-2.5 rounded-full transition-all duration-700"
                                 style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                    @endif

                    <p class="text-[11px] text-outline font-medium italic">
                        * Data magang hanya dapat diubah oleh administrator.
                    </p>
                </div>

                @elseif($user->isGuru())
                {{-- Info Pembimbing Sekolah (Khusus Guru) --}}
                <div class="bg-white rounded-2xl border border-outline-variant/50 shadow-sm p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-3 pb-3 border-b border-outline-variant/40">
                        <div class="w-10 h-10 rounded-xl bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-[20px]"
                                  style="font-variation-settings:'FILL' 1">supervisor_account</span>
                        </div>
                        <div>
                            <h4 class="text-[14px] sm:text-[15px] font-bold text-on-surface">Informasi Guru Pembimbing</h4>
                            <p class="text-[11px] sm:text-[12px] text-on-surface-variant">Data sekolah asal dan identitas pembimbing</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-[12px]">
                        <div class="bg-surface-container/40 p-3 rounded-xl">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-1">Asal Sekolah</p>
                            <p class="text-on-surface font-bold text-[13px] leading-tight">{{ $user->asal_sekolah ?? '—' }}</p>
                        </div>
                        <div class="bg-surface-container/40 p-3 rounded-xl">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-1">NIP</p>
                            <p class="text-on-surface font-bold text-[13px] leading-tight">{{ $user->nis_nip ?? '—' }}</p>
                        </div>
                    </div>

                    <p class="text-[11px] text-outline font-medium italic">
                        * Data sekolah asal dan NIP dapat diperbarui melalui Administrator.
                    </p>
                </div>

                @elseif($user->isKaryawan() || $user->isAdmin())
                {{-- Informasi Akun (Karyawan / Administrator) --}}
                <div class="bg-white rounded-2xl border border-outline-variant/50 shadow-sm p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-3 pb-3 border-b border-outline-variant/40">
                        <div class="w-10 h-10 rounded-xl {{ $user->isAdmin() ? 'bg-[#EDE9FE] text-[#5B21B6]' : 'bg-[#DBEAFE] text-[#1E40AF]' }} flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-[20px]"
                                  style="font-variation-settings:'FILL' 1">{{ $user->isAdmin() ? 'admin_panel_settings' : 'badge' }}</span>
                        </div>
                        <div>
                            <h4 class="text-[14px] sm:text-[15px] font-bold text-on-surface">Informasi Akun</h4>
                            <p class="text-[11px] sm:text-[12px] text-on-surface-variant">Detail status akun pengguna saat ini</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-[12px]">
                        <div class="bg-surface-container/40 p-3 rounded-xl">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-1">Peran Akun</p>
                            <p class="text-on-surface font-bold text-[13px] leading-tight">{{ $roleBadge['label'] }}</p>
                        </div>
                        <div class="bg-surface-container/40 p-3 rounded-xl">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-1">Terdaftar Sejak</p>
                            <p class="text-on-surface font-bold text-[13px] leading-tight">
                                {{ $user->created_at?->translatedFormat('d F Y') ?? '—' }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ── Logout Card (Mobile & Tablet view) ─────────────── --}}
                <div class="block lg:hidden bg-white rounded-2xl border border-outline-variant/50 shadow-sm p-5">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h4 class="text-[14px] font-bold text-on-surface">Keluar Sesi</h4>
                            <p class="text-[11px] sm:text-[12px] text-on-surface-variant">Log out dari aplikasi ini</p>
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

        </div>

    </div>
</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="profile" :role="auth()->user()->role" />
@endsection
