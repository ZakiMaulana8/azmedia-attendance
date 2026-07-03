<x-guest-layout>
<main class="flex-grow flex items-center justify-center px-md py-xl">
    <div class="w-full max-w-[400px]">

        {{-- Branding --}}
        <header class="flex flex-col items-center mb-xl">
            <div class="mb-lg">
                <div class="w-20 h-20 rounded-xl bg-primary-container flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-on-primary-container text-5xl"
                          style="font-variation-settings:'FILL' 1">
                        fingerprint
                    </span>
                </div>
            </div>
            <h1 class="text-headline-lg-mobile font-bold text-primary tracking-tight text-center">
                Azmedia Attendance
            </h1>
            <p class="text-body-sm text-on-surface-variant mt-xs text-center">
                Sistem Absensi Siswa Magang
            </p>
        </header>

        {{-- Error --}}
        @if ($errors->any())
            <div class="mb-md p-sm bg-error-container text-on-error-container rounded-lg text-body-sm flex items-center gap-sm">
                <span class="material-symbols-outlined text-[18px]">error</span>
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('status'))
            <div class="mb-md p-sm bg-primary-container text-on-primary-container rounded-lg text-body-sm">
                {{ session('status') }}
            </div>
        @endif

        {{-- Card --}}
        <div class="bg-surface-container-lowest p-lg rounded-xl shadow-sm border border-outline-variant space-y-lg">

            <div class="text-center space-y-xs">
                <p class="text-body-lg font-semibold text-on-surface">Masuk ke akun Anda</p>
                <p class="text-body-sm text-on-surface-variant">
                    Gunakan akun Google sekolah atau perusahaan untuk melanjutkan.
                </p>
            </div>

            {{-- Google Login Button --}}
            <a href="{{ route('auth.google.redirect') }}"
               class="w-full h-touch-target bg-surface-container-lowest hover:bg-surface-container
                      text-on-surface border border-outline-variant rounded-lg transition-all
                      active:scale-95 duration-150 flex items-center justify-center gap-md
                      shadow-sm font-semibold text-body-lg"
               x-data="{ loading: false }" @click="loading = true">
                <span x-show="!loading">
                    {{-- Google SVG logo --}}
                    <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                </span>
                <span class="material-symbols-outlined animate-spin text-primary" x-show="loading">
                    progress_activity
                </span>
                <span x-text="loading ? 'Mengarahkan...' : 'Login dengan Google'">Login dengan Google</span>
            </a>

            <div class="relative flex items-center gap-md">
                <div class="flex-1 border-t border-outline-variant"></div>
                <span class="text-label-md text-outline">Info</span>
                <div class="flex-1 border-t border-outline-variant"></div>
            </div>

            {{-- Info box --}}
            <div class="bg-surface-container-low rounded-lg p-sm border border-outline-variant space-y-xs">
                <p class="text-label-md text-on-surface-variant uppercase tracking-wider">Ketentuan Akses</p>
                <ul class="text-body-sm text-on-surface-variant space-y-xs">
                    <li class="flex items-start gap-sm">
                        <span class="material-symbols-outlined text-primary text-[16px] mt-0.5"
                              style="font-variation-settings:'FILL' 1">check_circle</span>
                        Siswa magang: gunakan email sekolah yang terdaftar
                    </li>
                    <li class="flex items-start gap-sm">
                        <span class="material-symbols-outlined text-primary text-[16px] mt-0.5"
                              style="font-variation-settings:'FILL' 1">check_circle</span>
                        Guru pembimbing: gunakan email yang diberikan admin
                    </li>
                    <li class="flex items-start gap-sm">
                        <span class="material-symbols-outlined text-secondary text-[16px] mt-0.5"
                              style="font-variation-settings:'FILL' 1">info</span>
                        Akun baru perlu diaktifasi oleh admin terlebih dahulu
                    </li>
                </ul>
            </div>
        </div>

        {{-- Geofence hint --}}
        <div class="mt-xl flex items-center justify-center gap-xs px-md py-sm rounded-full
                    bg-error-container/20 border border-error-container text-error">
            <span class="material-symbols-outlined text-[18px]"
                  style="font-variation-settings:'FILL' 1">location_on</span>
            <span class="text-status-badge font-bold">Pastikan Anda berada di area kantor</span>
        </div>

    </div>
</main>

<footer class="pb-safe py-md text-center">
    <p class="text-label-md text-outline">© {{ date('Y') }} Azmedia Attendance. All Rights Reserved.</p>
</footer>
</x-guest-layout>
