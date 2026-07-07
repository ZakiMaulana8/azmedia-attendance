<x-guest-layout>
<main class="flex-grow flex items-center justify-center px-md py-xl relative overflow-hidden">
    {{-- Ornamen Latar Belakang Premium --}}
    <div class="pointer-events-none absolute -top-40 -left-40 w-96 h-96 rounded-full bg-primary/5 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-40 -right-40 w-96 h-96 rounded-full bg-primary/5 blur-3xl"></div>

    <div class="w-full max-w-[400px] relative z-10">

        {{-- Branding --}}
        <header class="flex flex-col items-center mb-xl">
            <div class="mb-md">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg transition-transform duration-300 hover:scale-105"
                     style="background: #006c49; box-shadow: 0 8px 24px rgba(0, 108, 73, 0.25);">
                    <span class="material-symbols-outlined text-white text-[38px]"
                          style="font-variation-settings:'FILL' 1">
                        fingerprint
                    </span>
                </div>
            </div>
            <h1 class="text-[24px] font-black text-on-surface tracking-tight text-center"
                style="letter-spacing: -0.03em; line-height: 32px;">
                Azmedia <span class="text-primary">Attendance</span>
            </h1>
            <p class="text-[13px] text-on-surface-variant font-semibold mt-1 text-center">
                Sistem Absensi Siswa Magang
            </p>
        </header>

        {{-- Error --}}
        @if ($errors->any())
            <div class="mb-md p-md bg-red-50 border border-red-200 text-red-800 rounded-2xl text-[13px] font-bold flex items-center gap-sm shadow-sm">
                <span class="material-symbols-outlined text-[18px] text-red-600" style="font-variation-settings:'FILL' 1">error</span>
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('status'))
            <div class="mb-md p-md bg-primary/10 text-primary border border-primary/20 rounded-2xl text-[13px] font-bold shadow-sm">
                {{ session('status') }}
            </div>
        @endif

        {{-- Card --}}
        <div class="bg-white p-lg rounded-3xl shadow-md border border-outline-variant/50 space-y-lg">

            <div class="text-center space-y-1">
                <p class="text-[15px] font-extrabold text-on-surface">Masuk ke akun Anda</p>
                <p class="text-[12px] text-on-surface-variant leading-relaxed">
                    Gunakan akun Google sekolah atau perusahaan untuk melanjutkan.
                </p>
            </div>

            {{-- Google Login Button --}}
            <a href="{{ route('auth.google.redirect') }}"
               class="w-full h-touch-target bg-white hover:bg-surface-container/30
                      text-on-surface border border-outline-variant/60 rounded-2xl transition-all
                      active:scale-[0.98] duration-150 flex items-center justify-center gap-md
                      shadow-sm font-extrabold text-[13px]"
               x-data="{ loading: false }" @click="loading = true">
                <span x-show="!loading" class="flex-shrink-0">
                    {{-- Google SVG logo --}}
                    <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                </span>
                <span class="material-symbols-outlined animate-spin text-primary text-[18px]" x-show="loading">
                    progress_activity
                </span>
                <span x-text="loading ? 'Mengarahkan...' : 'Login dengan Google'">Login dengan Google</span>
            </a>

            <div class="relative flex items-center gap-md">
                <div class="flex-1 border-t border-outline-variant/40"></div>
                <span class="text-[11px] font-extrabold uppercase tracking-widest text-outline">Info</span>
                <div class="flex-1 border-t border-outline-variant/40"></div>
            </div>

            {{-- Info box --}}
            <div class="bg-surface-container/30 rounded-2xl p-md border border-outline-variant/40 space-y-sm">
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Ketentuan Akses</p>
                <ul class="text-[12px] text-on-surface-variant space-y-sm">
                    <li class="flex items-start gap-sm leading-relaxed">
                        <span class="material-symbols-outlined text-primary text-[16px] mt-0.5"
                              style="font-variation-settings:'FILL' 1">check_circle</span>
                        <span>Siswa magang: gunakan email sekolah yang terdaftar</span>
                    </li>
                    <li class="flex items-start gap-sm leading-relaxed">
                        <span class="material-symbols-outlined text-primary text-[16px] mt-0.5"
                              style="font-variation-settings:'FILL' 1">check_circle</span>
                        <span>Guru pembimbing: gunakan email yang diberikan admin</span>
                    </li>
                    <li class="flex items-start gap-sm leading-relaxed">
                        <span class="material-symbols-outlined text-secondary text-[16px] mt-0.5"
                              style="font-variation-settings:'FILL' 1">info</span>
                        <span>Akun baru perlu diaktifasi oleh admin terlebih dahulu</span>
                    </li>
                </ul>
            </div>

            @if(app()->environment('local'))
                <div class="mt-md space-y-sm">
                    <div class="relative flex items-center gap-md">
                        <div class="flex-1 border-t border-outline-variant/40"></div>
                        <span class="text-[11px] font-extrabold uppercase tracking-widest text-outline">Dev Login</span>
                        <div class="flex-1 border-t border-outline-variant/40"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-xs">
                        <a href="{{ route('dev.login', 'siswa') }}" class="py-sm px-xs bg-primary/10 hover:bg-primary/20 text-primary text-center rounded-xl text-[12px] font-bold transition-all">Siswa</a>
                        <a href="{{ route('dev.login', 'guru') }}" class="py-sm px-xs bg-secondary/10 hover:bg-secondary/20 text-secondary text-center rounded-xl text-[12px] font-bold transition-all">Guru</a>
                        <a href="{{ route('dev.login', 'admin') }}" class="py-sm px-xs bg-amber-500/10 hover:bg-amber-500/20 text-amber-600 text-center rounded-xl text-[12px] font-bold transition-all">Admin</a>
                    </div>
                </div>
            @endif

        </div>

        {{-- Geofence hint --}}
        <div class="mt-lg flex items-center justify-center gap-xs px-md py-sm rounded-2xl
                    bg-red-50 border border-red-100 text-red-600 shadow-sm">
            <span class="material-symbols-outlined text-[16px]"
                  style="font-variation-settings:'FILL' 1">location_on</span>
            <span class="text-[11px] font-bold uppercase tracking-wider">Pastikan Anda berada di area kantor</span>
        </div>

    </div>
</main>

<footer class="pb-safe py-md text-center">
    <p class="text-[11px] font-semibold text-outline">© {{ date('Y') }} Azmedia Attendance. All Rights Reserved.</p>
</footer>
</x-guest-layout>
