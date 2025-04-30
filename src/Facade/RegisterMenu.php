<?php

namespace Sukristyan\LaravelMenuWrapper\Facade;

use Illuminate\Support\Facades\Facade;

class RegisterMenu extends Facade
{
    protected static $menus = [];
    protected static $currentGroup = null;

    public static function groupping($label)
    {
        self::$currentGroup = $label;
        self::$menus[$label] = [];
    }

    public static function endGroupping()
    {
        self::$currentGroup = null;
    }

    public static function add($uri, $label)
    {
        if (self::$currentGroup) {
            self::$menus[self::$currentGroup][$uri] = $label;
        }
    }

    public static function all()
    {
        return self::$menus;
    }
}
