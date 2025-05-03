<?php

namespace Sukristyan\LaravelMenuWrapper\Facade;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Facade;
use Sukristyan\LaravelMenuWrapper\Exceptions\InvalidGroupException;

class RegisterMenu extends Facade
{
    protected static array $menus = [];
    protected static array $groups = ['index' => 0, 'group_name' => ''];

    protected static function getFacadeAccessor()
    {
        return 'sukristyan.menu';
    }

    public static function all(): array
    {
        return static::$menus;
    }

    protected static function add(Route $route, string $label, string $groupLabel = '')
    {
        if (!empty($groupLabel)) {
            self::createAsGroup($route, $label, $groupLabel);
        } else {
            static::$menus[] = array_merge(
                ['label' => $label],
                config('laravel-menu-wrapper.populate_items')($route)
            );
        }
    }

    private static function createAsGroup(Route $route, string $label, string $groupLabel): array
    {
        if (
            !in_array(
                $given = config('laravel-menu-wrapper.group_as', 'key'),
                $expect = ['item', 'key'],
                true
            )
        ) {
            throw InvalidGroupException::create($given, $expect);
        }

        foreach (static::$menus as $id => $menu) {
            if (is_array($menu) && ($menu['group_name'] ?? null) === $groupLabel) {
                static::$groups = ['id' => $id, 'group_name' => $groupLabel];
                return match ($given) {
                    'key' => self::groupAsKey($route, $label, $groupLabel),
                    'item' => self::groupAsItem($route, $label, $groupLabel),
                };
            }
        }

        $newId = count(static::$menus);
        static::$groups = ['id' => $newId, 'group_name' => $groupLabel];

        return match ($given) {
            'key' => self::groupAsKey($route, $label, $groupLabel),
            'item' => self::groupAsItem($route, $label, $groupLabel),
        };
    }

    private static function groupAsKey(Route $route, string $label, string $groupLabel): array
    {
        return static::$menus[$groupLabel][] = array_merge(
            ['label' => $label],
            config('laravel-menu-wrapper.populate_items')($route)
        );
    }

    private static function groupAsItem(Route $route, string $label, string $groupLabel): array
    {
        $id = static::$groups['id'];

        if (!isset(static::$menus[$id]['childs'])) {
            static::$menus[$id] = [
                'group_name' => $groupLabel,
                'childs' => []
            ];
        }

        static::$menus[$id]['childs'][] = array_merge(
            ['label' => $label],
            config('laravel-menu-wrapper.populate_items')($route)
        );
        return static::$menus[$id];
    }
}
