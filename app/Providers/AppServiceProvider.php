<?php

namespace App\Providers;

use App\Services\MediaStorage;
use App\Services\VercelBlobAuth;
use App\Services\VercelBlobClient;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(VercelBlobAuth::class);
        $this->app->singleton(VercelBlobClient::class);
        $this->app->singleton(MediaStorage::class);
     }

     /**
      * Bootstrap any application services.
      */
     public function boot(): void
     {
         if (config('app.env') === 'production' || env('APP_ENV') === 'production' || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
             URL::forceScheme('https');
         }
     }
}
