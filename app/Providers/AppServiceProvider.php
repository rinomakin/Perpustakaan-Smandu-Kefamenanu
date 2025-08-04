<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\PengaturanWebsite;

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
        // Share pengaturan website ke semua view
        View::composer('*', function ($view) {
            $pengaturan = PengaturanWebsite::first();
            $view->with('pengaturan', $pengaturan);
        });
    }
}
