<?php

namespace Sukristyan\LaravelMenuWrapper\Facade;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Facade;
use Sukristyan\LaravelMenuWrapper\Exceptions\InvalidGroupException;

class RegisterMenu extends Facade
{
    protected static array $menus = [];
    protected static string|null $currentGroup = null;

    public static function groupping(string $label): void
    {
        static::$currentGroup = $label;

        if (!isset(static::$menus[$label])) {
            static::$menus[$label] = [
                'label' => $label,
                'children' => [],
            ];
        }
    }

    public static function endGroup(): void
    {
        static::$currentGroup = null;
    }

    public static function add(Route $route, string $label): void
    {
        if (static::$currentGroup) {
            static::$menus[static::$currentGroup]['children'][] = [
                'route_name' => $route->getName(),
                'uri' => $route->uri(),
                'label' => $label,
            ];
        } else {
            static::$menus[] = [
                'route_name' => $route->getName(),
                'uri' => $route->uri(),
                'label' => $label,
            ];
        }
    }

    public static function all(): array
    {
        return static::$menus;
    }

    private static function groupAs()
    {
        if (!in_array($given = config('laravel-menu-wrapper.group_as'), $expect = ['item', 'key'])) {
            throw InvalidGroupException::create($given, $expect);
        }

        return config('laravel-menu-wrapper.group_as');
    }
}
