<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
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
        // Ensure index-backed string columns stay within MySQL's key length
        // limit when using the utf8mb4 charset.
        Schema::defaultStringLength(191);

        // Bootstrap-4 pagination markup (matches the AdminLTE theme).
        Paginator::useBootstrapFour();

        // Locked (primary Administrator) roles bypass all permission checks.
        Gate::before(function (User $user, string $ability) {
            return $user->isAdmin() ? true : null;
        });

        // Resolve any screen permission (e.g. "clients.view") against the
        // user's role. Route middleware uses can:<key>.
        Gate::define('access-screen', function (User $user, string $key) {
            return $user->hasPermission($key);
        });
    }
}
