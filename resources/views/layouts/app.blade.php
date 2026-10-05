<!DOCTYPE html>
<html lang="id" class="light">
<head>
    @PwaHead
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#006c49">
    <title>@yield('title', config('app.name', 'Azmedia Corpora'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    {{-- Override posisi & ukuran tombol install PWA agar tidak menutupi bottom navbar --}}
    <style>
        .box-icon {
            bottom: 86px !important;
            right: 14px !important;
        }
        .box-icon .circle {
            width: 38px !important;
            height: 38px !important;
            background-color: #006c49 !important;
            box-shadow: 0 2px 8px rgba(0,108,73,0.30) !important;
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
<body class="bg-background text-on-background min-h-screen flex flex-col"
      @hasSection('sidebar') x-data="{ navOpen: false }" @keydown.escape.window="navOpen = false" @endif>

    {{-- ── TopAppBar ─────────────────────────────────────────────── --}}
    <header class="fixed top-0 w-full z-50 bg-surface shadow-sm">
        <div class="flex justify-between items-center px-md h-touch-target w-full max-w-screen-xl mx-auto">
            <div class="flex items-center gap-md">
                @hasSection('sidebar')
                <button type="button" @click="navOpen = true" aria-label="Buka menu"
                        class="md:hidden material-symbols-outlined p-1 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors active:scale-95">
                    menu
                </button>
                @endif
                <div class="w-8 h-8 rounded-full overflow-hidden bg-surface-container">
                    <img class="w-full h-full object-cover"
                         src="{{ auth()->user()->photo_url }}"
                         alt="{{ auth()->user()->name }}">
                </div>
                <h1 class="font-bold text-headline-md text-primary">Azmedia Corpora</h1>
            </div>
            <button class="material-symbols-outlined p-2 rounded-full text-on-surface-variant
                           hover:bg-surface-container transition-colors active:scale-95 duration-150">
                notifications
            </button>
        </div>
    </header>

    {{-- ── Body wrapper ─────────────────────────────────────────── --}}
    <div class="flex flex-1 pt-touch-target">

        {{-- Desktop Sidebar --}}
        @hasSection('sidebar')
        <aside class="hidden md:flex flex-col w-64 bg-surface border-r border-outline-variant
                      h-[calc(100vh-48px)] sticky top-touch-target p-md gap-base shrink-0">
            @yield('sidebar')
        </aside>
        @endif

        {{-- Mobile Drawer (sumber menu sama dengan sidebar desktop) --}}
        @hasSection('sidebar')
        <div x-show="navOpen" x-cloak class="md:hidden fixed inset-0 z-[60]">
            <div class="absolute inset-0 bg-black/50" @click="navOpen = false"></div>
            <aside class="absolute left-0 top-0 h-full w-64 bg-surface p-md flex flex-col gap-base overflow-y-auto shadow-2xl"
                   x-transition:enter="transition ease-out duration-200"
                   x-transition:enter-start="-translate-x-full"
                   x-transition:enter-end="translate-x-0">
                <div class="flex items-center justify-between mb-sm">
                    <span class="font-bold text-primary">Azmedia Corpora</span>
                    <button type="button" @click="navOpen = false" aria-label="Tutup menu"
                            class="material-symbols-outlined p-1 rounded-full text-on-surface-variant">close</button>
                </div>
                @yield('sidebar')
            </aside>
        </div>
        @endif

        {{-- Main content --}}
        <main class="flex-1 pb-20 md:pb-0 overflow-y-auto">
            @yield('content')
        </main>
    </div>

    {{-- ── Bottom Nav (mobile) ──────────────────────────────────── --}}
    @yield('bottom-nav')

    @RegisterServiceWorkerScript
    @stack('scripts')
</body>
</html>
