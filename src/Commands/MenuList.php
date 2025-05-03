<?php

namespace Sukristyan\LaravelMenuWrapper\Commands;

use Illuminate\Console\Command;

class MenuList extends Command
{
    protected $signature = 'menu:list';
    protected $description = 'Show all menus you have defined';

    public function handle()
    {
        $menus = app('sukristyan.menu')->all();
        $groupAs = config('laravel-menu-wrapper.group_as');
        $withoutGroup = [];

        $this->line("\n<fg=yellow>--- Showing all menu ----</>");

        foreach ($menus as $key => $menu) {
            if ($groupAs === 'key') {
                is_string($key)
                    ? $this->showGroupsAsKey($key, $menu)
                    : $withoutGroup[] = $menu;
            } elseif ($groupAs === 'item') {
                isset($menu['group_name'])
                    ? $this->showGroupsAsItem($menu)
                    : $withoutGroup[] = $menu;
            }
        }

        $count = count($withoutGroup);
        $this->info("\nWithout Group: ({$count} items)");

        foreach ($withoutGroup as $menu) {
            $this->showChilds($menu);
        }

        return self::SUCCESS;
    }

    private function showGroupsAsKey(string $groupName, array $menu)
    {
        $this->info("\nGroup: {$groupName} (" . count($menu) . " items)");

        foreach ($menu as $child) {
            $this->showChilds($child);
        }
    }

    private function showGroupsAsItem(array $menu)
    {
        $this->info("\nGroup: {$menu['group_name']} (" . count($menu['childs']) . " items)");

        foreach ($menu['childs'] as $child) {
            $this->showChilds($child);
        }
    }

    private function showChilds(array $menu)
    {
        foreach (array_keys($menu) as $i => $key) {
            $prefix = $i === 0 ? '-' : ' ';
            $this->line("<fg=gray>{$prefix} {$key}:</> {$menu[$key]}");
        }
    }
}
