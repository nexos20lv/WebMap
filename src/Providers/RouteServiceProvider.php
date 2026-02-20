<?php

namespace Azuriom\Plugin\WebMap\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('web')
                ->prefix('webmap')
                ->name('webmap.')
                ->group(plugin_path('webmap') . '/routes/web.php');

            Route::prefix('admin/webmap')
                ->middleware('admin-access')
                ->name('webmap.admin.')
                ->group(plugin_path('webmap') . '/routes/admin.php');
        });
    }
}
