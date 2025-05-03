<?php

use Illuminate\Routing\Route;

return [
    /**
     * ----------------------------------------------------------------------
     * Determine the grouping method that will be used to group your menus
     * this configuration will applied only if you give the group label
     * on menu(). Refer to the README.md for more information
     * ----------------------------------------------------------------------
     * Possible value: key, item
     */
    'group_as' => 'key',

    'populate_items' => function (Route $route) {
        return [
            'route_name' => $route->getName(),
            'uri' => $route->uri(),
            'method' => $route->getActionMethod(),
        ];
    }
];
