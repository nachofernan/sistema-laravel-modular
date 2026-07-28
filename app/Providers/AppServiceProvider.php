<?php

namespace App\Providers;

use App\Models\Documentos\Categoria as DocumentoCategoria;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        //
        $request = request();

        $hostMap = [
            'intranet.local' => 'http://intranet.local',
            '172.17.8.80' => 'http://172.17.8.80/plataforma',
        ];

        $host = $request->getHost();

        if (isset($hostMap[$host])) {
            config(['app.url' => $hostMap[$host]]);
            URL::forceRootUrl($hostMap[$host]);
        }

        // Las categorías del menú de documentos se resuelven acá y no dentro de cada
        // Blade, que era donde vivía la query repetida en las tres navegaciones.
        View::composer([
            'components.navigation-links.guest',
            'layouts.partials.sidebar-navigation',
            'layouts.partials.sidebar-navigation-new',
        ], function ($view) {
            $view->with('categoriasPublicas', DocumentoCategoria::raicesPublicas()->get());
        });
    }
}
