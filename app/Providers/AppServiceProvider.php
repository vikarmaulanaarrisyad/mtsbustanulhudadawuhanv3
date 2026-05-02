<?php

namespace App\Providers;

use App\Models\Setting;
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
        view()->composer('*', function ($view) {
            // Tentukan layout secara dinamis
            $layout = 'layouts.app';
            if (auth()->check() && auth()->user()->hasRole('Guru')) {
                $layout = 'layouts.teacher';
            }
            
            $view->with('layout', $layout);
            $view->with('setting', Setting::first());
        });
    }
}
