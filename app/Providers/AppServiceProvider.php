<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;

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
        //
        $request = request();

        $hostMap = [
            'plataforma.local' => 'http://plataforma.local',
            '172.17.8.80'      => 'http://172.17.8.80/plataforma',
        ];

        $host = $request->getHost();

        if (isset($hostMap[$host])) {
            config(['app.url' => $hostMap[$host]]);
            URL::forceRootUrl($hostMap[$host]);
        }
    }
}
