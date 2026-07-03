<!DOCTYPE html>
<html lang="id" class="light">
<head>
    @PwaHead
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#006c49">
    <title>@yield('title', config('app.name', 'Azmedia Attendance'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col">

    {{-- ── TopAppBar ─────────────────────────────────────────────── --}}
    <header class="fixed top-0 w-full z-50 bg-surface shadow-sm">
        <div class="flex justify-between items-center px-md h-touch-target w-full max-w-screen-xl mx-auto">
            <div class="flex items-center gap-md">
                <div class="w-8 h-8 rounded-full overflow-hidden bg-surface-container">
                    <img class="w-full h-full object-cover"
                         src="{{ auth()->user()->photo_url }}"
                         alt="{{ auth()->user()->name }}">
                </div>
                <h1 class="font-bold text-headline-md text-primary">Azmedia Attendance</h1>
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
