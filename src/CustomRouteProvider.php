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
        $this->app->singleton('sukristyan.menu', function () {
            return new RegisterMenu();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::macro('groupMenu', function (string $label) {
            RegisterMenu::groupping($label);
            return $this;
        });

        Route::macro('menu', function (string $label) {
            RegisterMenu::add($this->uri(), $label);
            return $this;
        });

        Route::macro('group', function ($attributes, $routes) {
            $result = \Illuminate\Support\Facades\Route::buildGroup($attributes, $routes);
            RegisterMenu::endGroupping();
            return $result;
        });
    }
}
