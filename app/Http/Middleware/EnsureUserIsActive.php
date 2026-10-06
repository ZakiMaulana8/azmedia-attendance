<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Pastikan akun user yang sedang login berstatus aktif (is_active = true).
     * Jika akun dinonaktifkan, lakukan logout dan arahkan kembali ke login dengan pesan error.
     * Guest tidak ditangani oleh middleware ini.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if ($user->is_active === false) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda sedang dinonaktifkan. Hubungi Administrator.']);
        }

        return $next($request);
    }
}
