<?php

namespace Azuriom\Plugin\WebMap\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Illuminate\Support\Facades\Route;

class WebMapServiceProvider extends BasePluginServiceProvider
{
    /**
     * Register any plugin services.
     */
    public function register(): void
    {
        $this->registerMiddlewares();
    }

    /**
     * Bootstrap any plugin services.
     */
    public function boot(): void
    {
        $this->loadViews();
        $this->loadTranslations();
        $this->registerRouteDescriptions();
        $this->registerAdminNavigation();
        $this->registerUserNavigation();
    }

    /**
     * Returns the routes that should be able to be added to the navbar.
     *
     * @return array<string, string>
     */
    protected function routeDescriptions(): array
    {
        return [
            'webmap.index' => trans('webmap::messages.title'),
        ];
    }

    /**
     * Return the admin navigations routes to register in the dashboard.
     *
     * @return array<string, array<string, string>>
     */
    protected function adminNavigation(): array
    {
        return [
            'webmap' => [
                'name' => trans('webmap::admin.nav.title'),
                'icon' => 'bi bi-map',
                'route' => 'webmap.admin.settings',
                'permission' => 'admin.settings',
            ],
        ];
    }

    /**
     * Return the user navigations routes to register in the user menu.
     *
     * @return array<string, array<string, string>>
     */
    protected function userNavigation(): array
    {
        return [
            'webmap' => [
                'route' => 'webmap.index',
                'name' => trans('webmap::messages.nav_label'),
                'icon' => 'bi bi-map',
            ],
        ];
    }
}
