<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'admin'    => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'karyawan' => \App\Http\Middleware\EnsureUserIsKaryawan::class,
            'active'   => \App\Http\Middleware\EnsureUserIsActive::class,
        ]);
        $middleware->prependToPriorityList(
            before: \Illuminate\Routing\Middleware\SubstituteBindings::class,
            prepend: \App\Http\Middleware\EnsureUserIsAdmin::class,
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, Request $request) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['files' => 'Ukuran file yang Anda unggah terlalu besar. Silakan pilih file yang lebih kecil (maksimal 50 MB per file).']);
        });
    })->create();
