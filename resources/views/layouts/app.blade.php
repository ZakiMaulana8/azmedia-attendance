<!DOCTYPE html>
<html lang="id" class="light">
<head>
    @PwaHead
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#2563EB">
    <title>@yield('title', config('app.name', 'Azmedia Corpora'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        .box-icon {
            bottom: 86px !important;
            right: 14px !important;
        }
        .box-icon .circle {
            width: 38px !important;
            height: 38px !important;
            background-color: #2563EB !important;
            box-shadow: 0 2px 8px rgba(37,99,235,0.30) !important;
        }
        .box-icon .circle img {
            max-width: 20px !important;
            max-height: 20px !important;
        }
        .box-icon .circle:after {
            animation: none !important;
            box-shadow: none !important;
        }
    </style>
</head>
<body style="background: #F4F8FF; color: #0F172A; min-height: 100vh; display: flex; flex-direction: column;"
      @hasSection('sidebar') x-data="{ navOpen: false }" @keydown.escape.window="navOpen = false" @endif>

    {{-- ── TopAppBar ─────────────────────────────────────────────── --}}
    <header class="fixed top-0 w-full z-50 bg-white border-b border-slate-200/80 shadow-xs" style="height: 52px;">
        <div class="flex justify-between items-center px-4 w-full max-w-screen-xl mx-auto" style="height: 52px;">
            {{-- Kiri: Logo Azmedia Corporate + Brand --}}
            <div class="flex items-center gap-2.5">
                @hasSection('sidebar')
                <button type="button" @click="navOpen = true" aria-label="Buka menu"
                        class="md:hidden material-symbols-outlined p-1 rounded-full text-slate-600 hover:bg-slate-100 transition-colors active:scale-95 text-[20px]">
                    menu
                </button>
                @endif
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('images/azmedia-logo-transparent.png') }}"
                         alt="Azmedia Corporate Logo"
                         style="height: 30px;"
                         class="w-auto object-contain">
                    <span class="font-extrabold tracking-tight text-[#0F172A]"
                          style="font-size: 17px; line-height: 1;">
                        Azmedia <span class="text-[#2563EB]">Corporate</span>
                    </span>
                </div>
            </div>

            {{-- Kanan: Notifikasi + Avatar Profile User --}}
            <div class="flex items-center gap-2">
                <div class="relative" style="position: relative;"
                     x-data="{ open: false }"
                     @click.outside="open = false"
                     @keydown.escape.window="open = false">
                    <button type="button" @click="open = !open"
                            aria-label="Menu akun" :aria-expanded="open"
                            class="w-8 h-8 rounded-full overflow-hidden flex items-center justify-center flex-shrink-0 transition-all active:scale-95 focus:outline-none"
                            style="background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%); border: 2px solid #BFDBFE; box-shadow: 0 2px 6px rgba(37,99,235,0.25); cursor: pointer;">
                        @if(auth()->user()->foto && auth()->user()->foto !== 'default.png')
                            <img class="w-full h-full object-cover" src="{{ auth()->user()->photo_url }}" alt="{{ auth()->user()->name }}">
                        @else
                            <span class="font-extrabold text-white text-[12px] uppercase leading-none">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        @endif
                    </button>

                    {{-- Dropdown akun --}}
                    <div x-show="open" style="display: none; position: absolute; right: 0; top: 44px; width: 240px; z-index: 70;
                                              background: #fff; border: 1px solid #E2E8F0; border-radius: 16px;
                                              box-shadow: 0 12px 32px rgba(15,23,42,0.14); overflow: hidden;"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100">
                        <div style="padding: 14px 16px; background: #F8FAFC; border-bottom: 1px solid #E2E8F0;">
                            <p style="font-size: 13px; font-weight: 800; color: #0F172A; margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ auth()->user()->name }}</p>
                            <p style="font-size: 11px; color: #64748B; margin: 2px 0 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ auth()->user()->email }}</p>
                        </div>
                        <div style="padding: 6px;">
                            <a href="{{ route('profile.edit') }}"
                               class="hover:bg-slate-50 transition-colors"
                               style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px; font-size: 13px; font-weight: 700; color: #334155; text-decoration: none;">
                                <span class="material-symbols-outlined" style="font-size: 18px;">person</span>
                                Profil Saya
                            </a>
                            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                @csrf
                                <button type="submit"
                                        class="hover:bg-red-50 transition-colors"
                                        style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px; font-size: 13px; font-weight: 700; color: #DC2626; background: transparent; border: 0; cursor: pointer; text-align: left;">
                                    <span class="material-symbols-outlined" style="font-size: 18px;">logout</span>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- ── Body wrapper ─────────────────────────────────────────── --}}
    <div class="flex flex-1" style="padding-top: 52px;">

        {{-- Desktop Sidebar --}}
        @hasSection('sidebar')
        <aside class="hidden md:flex flex-col w-64 bg-white border-r border-slate-200 p-4 gap-4 shrink-0"
               style="position: sticky; top: 52px; height: calc(100vh - 52px); overflow-y: auto;">
            @yield('sidebar')
        </aside>
        @endif

        {{-- Mobile Drawer --}}
        @hasSection('sidebar')
        <div x-show="navOpen" x-cloak class="md:hidden fixed inset-0 z-[60]">
            <div class="absolute inset-0 bg-black/50" @click="navOpen = false"></div>
            <aside class="absolute left-0 top-0 h-full w-64 bg-white p-4 flex flex-col gap-4 overflow-y-auto shadow-2xl"
                   x-transition:enter="transition ease-out duration-200"
                   x-transition:enter-start="-translate-x-full"
                   x-transition:enter-end="translate-x-0">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-extrabold text-[#0F172A]">Azmedia <span class="text-[#2563EB]">Corporate</span></span>
                    <button type="button" @click="navOpen = false" aria-label="Tutup menu"
                            class="material-symbols-outlined p-1 rounded-full text-slate-600">close</button>
                </div>
                @yield('sidebar')
            </aside>
        </div>
        @endif

        {{-- Main content --}}
        <main class="flex-1 pb-20 md:pb-0">
            @yield('content')
        </main>
    </div>

    {{-- ── Bottom Nav (mobile) ──────────────────────────────────── --}}
    @yield('bottom-nav')

    @RegisterServiceWorkerScript
    @stack('scripts')
</body>
</html>
