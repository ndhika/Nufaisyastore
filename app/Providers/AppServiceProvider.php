<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Override default auth middleware behavior
    app()->extend(MiddlewareAuthenticate::class, function ($middleware) {
        return new class extends MiddlewareAuthenticate {
            protected function redirectTo(Request $request)
            {
                if (!$request->expectsJson()) {
                    // Simpan halaman tujuan sebelum login
                    Session::put('url.intended', $request->fullUrl());

                    return route('login');
                }
            }
        };
    });
    }
}
