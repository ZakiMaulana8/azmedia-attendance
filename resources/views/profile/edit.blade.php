@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div class="max-w-lg mx-auto">

    {{-- ── Hero Header ─────────────────────────────────────────────── --}}
    <section class="relative bg-primary overflow-hidden px-md pt-lg pb-lg"
             style="border-radius: 0 0 32px 32px;">
        {{-- Ornamen --}}
        <div class="pointer-events-none absolute -top-10 -right-10 w-44 h-44 rounded-full"
             style="background: rgba(255,255,255,0.07)"></div>
        <div class="pointer-events-none absolute top-6 -right-2 w-24 h-24 rounded-full"
             style="background: rgba(255,255,255,0.05)"></div>

        <div class="relative z-10">
            <p class="text-white/60 text-[11px] font-semibold uppercase tracking-widest mb-1">
                Akun & Pengaturan
            </p>
            <h2 class="text-white font-extrabold"
                style="font-size: 24px; line-height: 30px; letter-spacing: -0.02em;">
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
            <p class="text-white/60 text-[13px] mt-1">{{ $profileSubtitle }}</p>
        </div>
    </section>

    <div class="px-md pt-lg pb-xl space-y-lg">

        {{-- Flash status --}}
        @if(session('status'))
        <div class="flex items-center gap-sm bg-emerald-50 border border-emerald-200 rounded-2xl px-md py-sm">
            <span class="material-symbols-outlined text-emerald-600 text-[18px] flex-shrink-0"
                  style="font-variation-settings:'FILL' 1">check_circle</span>
            <p class="text-[13px] text-emerald-800 font-semibold">{{ session('status') }}</p>
        </div>
        @endif

        {{-- ── Avatar & Identitas Card ─────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-outline-variant/50 shadow-sm p-lg flex flex-col items-center text-center gap-sm relative overflow-hidden">
            {{-- Background decorative touch --}}
            <div class="absolute -right-4 -top-4 w-12 h-12 bg-primary/5 rounded-full pointer-events-none"></div>
            
            <div class="relative">
                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-primary/10 shadow-md">
                    <img src="{{ $user->photo_url }}" alt="{{ $user->name }}"
                         class="w-full h-full object-cover">
                </div>
                {{-- Status badge icon overlay --}}
                <div class="absolute bottom-0 right-0 w-7 h-7 rounded-full bg-primary flex items-center justify-center border-2 border-white text-white">
                    <span class="material-symbols-outlined text-[14px]" style="font-variation-settings:'FILL' 1">verified</span>
                </div>
            </div>

            <div>
                <h3 class="text-[18px] font-extrabold text-on-surface leading-tight">{{ $user->name }}</h3>
                <p class="text-[12px] text-on-surface-variant font-medium mt-1">{{ $user->email }}</p>
            </div>

            {{-- Role badge --}}
            @php
                $roleBadge = match($user->role) {
                    'admin'    => ['label' => 'Administrator',    'class' => 'bg-tertiary-container text-on-tertiary-container', 'icon' => 'admin_panel_settings'],
                    'guru'     => ['label' => 'Guru Pembimbing', 'class' => 'bg-secondary-container text-on-secondary-container', 'icon' => 'supervisor_account'],
                    'karyawan' => ['label' => 'Karyawan',        'class' => 'bg-emerald-100 text-emerald-800 border border-emerald-200/80', 'icon' => 'badge'],
                    default    => ['label' => 'Siswa Magang',    'class' => 'bg-primary-container text-on-primary-container', 'icon' => 'school'],
                };
            @endphp
            <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold px-3 py-1.5 rounded-full {{ $roleBadge['class'] }}">
                <span class="material-symbols-outlined text-[14px]">{{ $roleBadge['icon'] }}</span>
                {{ $roleBadge['label'] }}
            </span>
            
            <div class="flex items-center gap-xs text-[11px] text-outline font-semibold">
                <span class="material-symbols-outlined text-[14px]">login</span>
                Login via Google
            </div>
        </div>

        {{-- ── Edit Form Card (Nama & Telepon) ────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-outline-variant/50 shadow-sm overflow-hidden">
            <div class="flex items-center gap-sm px-md py-md border-b border-outline-variant/40">
                <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-[18px]"
                          style="font-variation-settings:'FILL' 1">manage_accounts</span>
                </div>
                <div>
                    <p class="text-[14px] font-bold text-on-surface">Ubah Informasi Profil</p>
                    <p class="text-[11px] text-on-surface-variant">Update nama dan nomor kontak Anda</p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="p-md space-y-md">
                @csrf
                @method('PATCH')

                {{-- Nama Lengkap --}}
                <div>
                    <label for="name" class="block text-[12px] font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Nama Lengkap
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">
                            person
                        </span>
                        <input id="name" type="text" name="name"
                               value="{{ old('name', $user->name) }}"
                               class="w-full h-touch-target pl-10 pr-md rounded-2xl border border-outline-variant/60
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
                <div>
                    <label class="block text-[12px] font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Email
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">
                            mail
                        </span>
                        <input type="text" value="{{ $user->email }}" disabled
                               class="w-full h-touch-target pl-10 pr-10 rounded-2xl border border-outline-variant/40
                                      bg-surface-container text-on-surface-variant/70 text-[14px] font-semibold cursor-not-allowed">
                        <span class="material-symbols-outlined absolute right-md top-1/2 -translate-y-1/2 text-outline text-[18px]">lock</span>
                    </div>
                    <p class="text-[11px] text-outline mt-1.5 ml-1">Email dikelola oleh Google, tidak dapat diubah.</p>
                </div>

                {{-- Nomor Telepon --}}
                <div>
                    <label for="phone" class="block text-[12px] font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Nomor Telepon
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">
                            call
                        </span>
                        <input id="phone" type="tel" name="phone"
                               value="{{ old('phone', $user->phone) }}"
                               placeholder="Contoh: 081234567890"
                               class="w-full h-touch-target pl-10 pr-md rounded-2xl border border-outline-variant/60
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

                <button type="submit"
                        class="w-full h-touch-target rounded-2xl font-extrabold text-[14px] text-white
                               flex items-center justify-center gap-sm
                               transition-all duration-200 active:scale-[0.98]"
                        style="background: #006c49; box-shadow: 0 6px 24px rgba(0,108,73,0.35);">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- ── Info Magang Card (Khusus Siswa) ─────────────────────────── --}}
        @if($user->isSiswa())
        <div class="bg-white rounded-2xl border border-outline-variant/50 shadow-sm p-md space-y-md">
            <div class="flex items-center gap-sm pb-sm border-b border-outline-variant/40">
                <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-[16px]"
                          style="font-variation-settings:'FILL' 1">work_history</span>
                </div>
                <div>
                    <h4 class="text-[13px] font-bold text-on-surface">Data Magang</h4>
                    <p class="text-[11px] text-on-surface-variant">Informasi magang yang aktif</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-sm text-[12px]">
                <div class="bg-surface-container/40 p-sm rounded-xl">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-0.5">Asal Sekolah</p>
                    <p class="text-on-surface font-bold leading-tight">{{ $user->asal_sekolah ?? '—' }}</p>
                </div>
                <div class="bg-surface-container/40 p-sm rounded-xl">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-0.5">Jurusan</p>
                    <p class="text-on-surface font-bold leading-tight">{{ $user->jurusan ?? '—' }}</p>
                </div>
                <div class="bg-surface-container/40 p-sm rounded-xl">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-0.5">NIS</p>
                    <p class="text-on-surface font-bold leading-tight">{{ $user->nis_nip ?? '—' }}</p>
                </div>
                <div class="bg-surface-container/40 p-sm rounded-xl">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-0.5">Sisa Hari</p>
                    @php $sisa = $user->sisaHariMagang(); @endphp
                    <p class="font-bold leading-tight {{ $sisa !== null && $sisa <= 7 ? 'text-red-600' : 'text-primary' }}">
                        {{ $sisa !== null ? $sisa . ' hari' : '—' }}
                    </p>
                </div>
                <div class="bg-surface-container/40 p-sm rounded-xl">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-0.5">Mulai Magang</p>
                    <p class="text-on-surface font-bold leading-tight">
                        {{ $user->tanggal_mulai?->translatedFormat('d M Y') ?? '—' }}
                    </p>
                </div>
                <div class="bg-surface-container/40 p-sm rounded-xl">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-outline mb-0.5">Selesai Magang</p>
                    <p class="text-on-surface font-bold leading-tight">
                        {{ $user->tanggal_selesai?->translatedFormat('d M Y') ?? '—' }}
                    </p>
                </div>
            </div>

            @if($user->tanggal_mulai && $user->tanggal_selesai)
            @php $progress = $user->progressMagang(); @endphp
            <div class="pt-sm border-t border-outline-variant/30">
                <div class="flex justify-between text-[11px] text-on-surface-variant font-bold mb-1.5">
                    <span>Progress Magang</span>
                    <span class="text-primary">{{ $progress }}%</span>
                </div>
                <div class="w-full bg-surface-container rounded-full h-2">
                    <div class="bg-primary h-2 rounded-full transition-all duration-700"
                         style="width: {{ $progress }}%"></div>
                </div>
            </div>
            @endif

            <p class="text-[10px] text-outline font-semibold italic">
                * Data magang hanya dapat diubah oleh admin.
            </p>
        </div>
        @endif

        {{-- ── Logout Card ─────────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-outline-variant/50 shadow-sm p-md">
            <div class="flex items-center justify-between gap-sm">
                <div>
                    <h4 class="text-[13px] font-bold text-on-surface">Keluar Sesi</h4>
                    <p class="text-[11px] text-on-surface-variant">Log out dari aplikasi ini</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit"
                            class="h-10 px-md border border-red-200 text-red-600 rounded-xl font-bold text-[13px]
                                   flex items-center gap-xs hover:bg-red-50 active:scale-95 transition-all">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
                        Keluar
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="profile" :role="auth()->user()->role" />
@endsection
