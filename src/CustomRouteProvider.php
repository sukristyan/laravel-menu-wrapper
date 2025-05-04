<?php

namespace Sukristyan\LaravelMenuWrapper;

use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;
use Sukristyan\LaravelMenuWrapper\Facade\RegisterMenu;

final class CustomRouteProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerConfig();

        $this->app->singleton('sukristyan.menu', function () {
            return new RegisterMenu();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishConfig();

        Route::macro('menu', function (string $label, string $groupLabel = '') {
            /** @var \Illuminate\Routing\Route $this */
            RegisterMenu::add($this, $label, $groupLabel);
            return $this;
        });
    }

    public function registerConfig()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-menu-wrapper.php',
            'laravel-menu-wrapper'
        );
    }

    public function publishConfig()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../config/laravel-menu-wrapper.php' => config_path('laravel-menu-wrapper.php'),
        ], 'laravel-menu-wrapper');
    }
}
