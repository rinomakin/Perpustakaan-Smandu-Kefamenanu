<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;

class BarcodeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('barcode', function ($app) {
            return new DNS1D();
        });
        
        $this->app->singleton('qrcode', function ($app) {
            return new DNS2D();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
} 