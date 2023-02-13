<?php

namespace GreyZmeem\Seat\Doctrine;

use Seat\Services\AbstractSeatPlugin;

class DoctrineServiceProvider extends AbstractSeatPlugin
{
    public function boot()
    {
        $this->add_routes();
        $this->add_views();
        $this->add_migrations();
        $this->add_publications();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/doctrine.config.php', 'doctrine.config');
        $this->mergeConfigFrom(__DIR__ . '/Config/doctrine.sidebar.php', 'package.sidebar');
        $this->registerPermissions(__DIR__ . '/Config/doctrine.permissions.php', 'doctrine');
    }

    private function add_routes()
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
    }

    private function add_views()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'doctrine');
    }

    private function add_migrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');
    }

    public function add_publications()
    {
        $this->publishes([
            __DIR__ . '/resources/css' => public_path('web/doctrine/css'),
            __DIR__ . '/resources/img' => public_path('web/doctrine/img'),
        ], ['public', 'seat']);
    }

    public function getName(): string
    {
        return 'SeAT Doctrine';
    }

    public function getPackageRepositoryUrl(): string
    {
        return 'https://github.com/GreyZmeem/seat-doctrine';
    }

    public function getPackagistPackageName(): string
    {
        return 'seat-doctrine';
    }

    public function getPackagistVendorName(): string
    {
        return 'greyzmeem';
    }

    public function getVersion(): string
    {
        return config('doctrine.config.version');
    }
}
