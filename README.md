# Laravel Menu Wrapper

Laravel Menu Wrapper is a lightweight package that helps you manage dynamic menus in your Laravel app with less effort.

> Simply put, this package automatically generates an array of menus based on what you define.

Tested on Laravel `10.x`, `11.x`, and `12.x` — works successfully without any issues.

# Installation

Using Composer (recommended):

```sh
cd your-laravel-project
composer require sukristyan/laravel-menu-wrapper
```

After the installation is complete, the provider and configuration files will be published automatically.

# Configuration

Currently, there are only 2 configuration items.

## Group As (`group_as`)

Values: `item` or `key`.

This setting determines how your menus will be grouped.

`key` means that your group label will be used as the array key. Your defined menu will look like this:

```php
[
    'Group Name' => [
        [
            'label' => 'Menu #1',
        ],
        [
            'label' => 'Menu #2',
        ]
    ],
    ...
]
```

`item` means that your group label will be included in the array as `group_name`, and your defined menus will be inserted under `childs`. Your menu will look like this:

```php
[
    [
        'group_name' => 'Group Name',
        'childs' => [
            [
                'label' => 'Menu #1',
            ],
            [
                'label' => 'Menu #2',
            ],
        ],
    ],
    ...
]
```

## Populated Items (`populated_items`)

Here, you can define what you want to collect from the 'Illuminate\Routing\Route', or add custom identifiers to help integrate the menu into your application.

```php
'populate_items' => function (Route $route) {
    return [
        'route_name' => $route->getName(),
        'type' => 'children'
    ];
}
```

# Usage

> NOTE: All collected data will follow what you define in `config/laravel-menu-wrapper.php`.

Go to your 'routes/web.php', and add the `menu('Menu Label', 'Group Label')` function to your route:

```php
Route::get('index', [DashboardController::class, 'index'])
    ->name('index')
    ->menu('Dashboard');
```

After that, you can run `php artisan menu:list` to show all your menus. You can also get all menus using this code:

```php
app('sukristyan.menu')->all();
```

You can parse the resulting menu array and use it anywhere in your project.

You can directly use it in a `foreach` loop, or create a custom page to manage the menu — allowing administrators to add or remove menu items for users.

```php
$menus = app('sukristyan.menu')->all();

foreach ($menus as $menu) {
    ...
}
```

# License

The `sukristyan/laravel-menu-wrapper` package is open-sourced software licensed under the [MIT license](https://github.com/sukristyan/laravel-menu-wrapper/blob/master/LICENSE).
