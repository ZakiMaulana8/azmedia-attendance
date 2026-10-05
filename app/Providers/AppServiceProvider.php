<?php

namespace App\Providers;

use App\Models\Permission;
use App\Policies\PermissionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Permission::class, PermissionPolicy::class);

        // Set locale Carbon ke Indonesia
        Carbon::setLocale('id');

        if (str_contains(config('app.url'), 'ngrok') || request()->header('x-forwarded-proto') === 'https') {
            \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
