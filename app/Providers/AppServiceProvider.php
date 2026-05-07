<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Gate;
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
        // Super Admin bypass
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        // Registrasi Google Drive Driver
        try {
            \Illuminate\Support\Facades\Storage::extend('google', function ($app, $config) {
                $client = new \Google\Client();
                $client->setAuthConfig($config['serviceAccountJson']);
                $client->addScope(\Google\Service\Drive::DRIVE);

                $service = new \Google\Service\Drive($client);
                $options = [
                    'supportsAllDrives' => true,
                    'supportsTeamDrives' => true,
                ];
                $adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service, $config['folderId'] ?? '/', $options);
                $driver = new \League\Flysystem\Filesystem($adapter);

                return new \Illuminate\Filesystem\FilesystemAdapter($driver, $adapter);
            });
        } catch (\Exception $e) {
            // Silently fail if dependencies missing during boot
        }

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
