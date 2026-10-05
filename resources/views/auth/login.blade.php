<x-guest-layout>
<main class="min-h-screen bg-[#F4F8FC] flex flex-col justify-between relative overflow-hidden font-['Inter',sans-serif]">

    {{-- ── Background Waves & Accent Ornaments ────────────────────────────────── --}}
    
    {{-- Top-Left Blue Swoosh Wave --}}
    <div class="pointer-events-none absolute top-0 left-0 w-[350px] sm:w-[500px] h-[350px] sm:h-[500px] z-0">
        <svg class="w-full h-full" viewBox="0 0 500 500" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M-100 -100 L350 -100 C350 150 150 350 -100 350 Z" fill="url(#tl-gradient)" />
            <defs>
                <linearGradient id="tl-gradient" x1="-100" y1="-100" x2="350" y2="350" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#1D4ED8" />
                    <stop offset="60%" stop-color="#2563EB" />
                    <stop offset="100%" stop-color="#60A5FA" stop-opacity="0.8" />
                </linearGradient>
            </defs>
        </svg>
    </div>

    {{-- Bottom-Right Blue Swoosh Wave --}}
    <div class="pointer-events-none absolute bottom-0 right-0 w-[400px] sm:w-[600px] h-[400px] sm:h-[600px] z-0">
        <svg class="w-full h-full" viewBox="0 0 600 600" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M700 700 L200 700 C200 400 400 200 700 200 Z" fill="url(#br-gradient)" />
            <defs>
                <linearGradient id="br-gradient" x1="700" y1="700" x2="200" y2="200" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#1E40AF" />
                    <stop offset="50%" stop-color="#2563EB" />
                    <stop offset="100%" stop-color="#3B82F6" stop-opacity="0.9" />
                </linearGradient>
            </defs>
        </svg>
    </div>

    {{-- Top-Right Dot Matrix Grid --}}
    <div class="pointer-events-none absolute top-10 right-14 z-0 hidden sm:grid grid-cols-6 gap-3 opacity-30">
        @for ($i = 0; $i < 18; $i++)
            <div class="w-2 h-2 rounded-full bg-[#60A5FA]"></div>
        @endfor
    </div>

    {{-- Bottom-Left Dot Matrix Grid --}}
    <div class="pointer-events-none absolute bottom-12 left-10 z-0 hidden sm:grid grid-cols-6 gap-3 opacity-30">
        @for ($i = 0; $i < 18; $i++)
            <div class="w-2 h-2 rounded-full bg-[#60A5FA]"></div>
        @endfor
    </div>

    {{-- ── Main Content Container ─────────────────────────────────────────────── --}}
    <div class="flex-grow flex flex-col items-center justify-center px-4 py-10 relative z-10 w-full max-w-screen-xl mx-auto">

        {{-- Branding Header --}}
        <header class="flex flex-col items-center mb-8 sm:mb-9 text-center">
            {{-- Official Azmedia Corporate Logo (Transparent PNG, No White Box) --}}
            <div class="mb-4.5 sm:mb-5 flex justify-center w-full">
                <img src="{{ asset('images/azmedia-logo-transparent.png') }}"
                     alt="Azmedia Corporate Logo"
                     class="w-[125px] sm:w-auto sm:max-w-[160px] h-auto object-contain mx-auto transition-transform duration-300 hover:scale-[1.02] bg-transparent border-0 shadow-none outline-none">
            </div>

            {{-- Text: Azmedia Corporate --}}
            <h1 class="text-[26px] sm:text-[28px] font-extrabold text-slate-900 tracking-tight leading-none">
                Azmedia <span class="text-[#2563EB]">Corporate</span>
            </h1>
        </header>

        {{-- Session Flash Messages --}}
        @if ($errors->any())
            <div class="w-full max-w-[430px] mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl text-[13px] font-bold flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined text-[20px] text-red-600 shrink-0" style="font-variation-settings:'FILL' 1">error</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        @if (session('status'))
            <div class="w-full max-w-[430px] mb-4 p-4 bg-blue-50 text-blue-800 border border-blue-200 rounded-2xl text-[13px] font-bold shadow-sm flex items-center gap-3">
                <span class="material-symbols-outlined text-[20px] text-blue-600 shrink-0" style="font-variation-settings:'FILL' 1">info</span>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        {{-- ── Card Container ────────────────────────────────────────────────── --}}
        <div class="w-full max-w-[430px] bg-white rounded-[26px] p-7 sm:p-8 shadow-[0_15px_45px_-10px_rgba(37,99,235,0.12)] border border-slate-100 space-y-6">

            {{-- Card Header: User Icon + Text --}}
            <div class="flex items-start gap-4">
                <div class="w-11 h-11 rounded-full bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center shrink-0 mt-0.5">
                    <span class="material-symbols-outlined text-[24px]" style="font-variation-settings:'FILL' 1">person</span>
                </div>
                <div>
                    <h2 class="text-[16px] font-extrabold text-[#1E293B] leading-snug">Masuk ke akun Anda</h2>
                    <p class="text-[12px] text-[#64748B] font-normal leading-relaxed mt-0.5">
                        Gunakan akun Google sekolah atau perusahaan untuk melanjutkan.
                    </p>
                </div>
            </div>

            {{-- Google Login Button --}}
            <a href="{{ route('auth.google.redirect') }}"
               class="w-full h-12 bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold text-[14px] rounded-2xl flex items-center justify-between px-4 sm:px-5 shadow-lg shadow-blue-500/25 transition-all active:scale-[0.99] duration-150 group"
               x-data="{ loading: false }" @click="loading = true">
                <div class="w-7 h-7 rounded-full bg-white flex items-center justify-center shrink-0 shadow-xs">
                    {{-- Google SVG logo --}}
                    <svg class="w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                </div>
                <span class="flex-grow text-center font-bold" x-text="loading ? 'Mengarahkan...' : 'Lanjutkan dengan Google'">Lanjutkan dengan Google</span>
                <span class="material-symbols-outlined text-[20px] transition-transform group-hover:translate-x-0.5" x-show="!loading">arrow_forward</span>
                <span class="material-symbols-outlined animate-spin text-[18px]" x-show="loading">progress_activity</span>
            </a>

            {{-- Divider --}}
            <div class="relative flex items-center gap-3 my-2">
                <div class="flex-1 border-t border-slate-200/80"></div>
                <span class="text-[11px] font-extrabold tracking-widest text-[#94A3B8] uppercase">INFO</span>
                <div class="flex-1 border-t border-slate-200/80"></div>
            </div>

            {{-- Info Box --}}
            <div class="bg-[#F0F7FF] border border-[#DBEAFE] rounded-2xl p-4 space-y-3.5 text-left">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#2563EB] text-[20px]" style="font-variation-settings:'FILL' 1">verified_user</span>
                    <span class="text-[13px] font-extrabold text-[#1E293B]">Ketentuan Akses</span>
                </div>
                <ul class="space-y-2.5 text-[12.5px] text-[#475569] font-medium">
                    <li class="flex items-start gap-2.5 leading-snug">
                        <span class="material-symbols-outlined text-[#10B981] text-[18px] shrink-0 mt-0.5" style="font-variation-settings:'FILL' 1">check_circle</span>
                        <span>Siswa magang: gunakan email sekolah yang terdaftar</span>
                    </li>
                    <li class="flex items-start gap-2.5 leading-snug">
                        <span class="material-symbols-outlined text-[#10B981] text-[18px] shrink-0 mt-0.5" style="font-variation-settings:'FILL' 1">check_circle</span>
                        <span>Guru pembimbing: gunakan email yang diberikan admin</span>
                    </li>
                    <li class="flex items-start gap-2.5 leading-snug">
                        <span class="material-symbols-outlined text-[#2563EB] text-[18px] shrink-0 mt-0.5" style="font-variation-settings:'FILL' 1">info</span>
                        <span>Akun baru perlu diaktifasi oleh admin terlebih dahulu</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    {{-- Footer --}}
    <footer class="py-5 text-center text-[#94A3B8] text-[11.5px] font-medium relative z-10 space-y-0.5">
        <p>© {{ date('Y') }} Azmedia</p>
        <p>Sistem Absensi Azmedia</p>
    </footer>

</main>
</x-guest-layout>
