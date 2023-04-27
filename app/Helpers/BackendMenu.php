<?php


namespace App\Helpers;


use App\Constants\AuthorityType;

class BackendMenu
{

    public static function menu()
    {
        return [
            [
                'name' => 'Dashboard',
                'type' => 'single',
                'icon' => 'icon-home',
                'url' => route('backend.dashboard'),
                'groups' => [
                    AuthorityType::DASHBOARD
                ]
            ],
            [
                'name' => 'User',
                'type' => 'single',
                'icon' => 'icon-page-break2',
                'url' => route('backend.user'),
                'groups' => [
                    AuthorityType::USER,
                ]
            ], [
                'name' => 'Restaurant',
                'type' => 'single',
                'icon' => 'icon-page-break2',
                'url' => route('backend.restaurant'),
                'groups' => [
                    AuthorityType::RESTAURANT,
                ]
            ],
            [
                'name' => 'Catalog',
                'type' => 'multiple',
                'icon' => 'icon-page-break2',
                'items' => [
                    [
                        'name' => 'Restaurant Menu',
                        'url' => route('backend.menu'),
                        'groups' => [
                            AuthorityType::PRODUCT,
                        ]
                    ]
                ]
            ]
        ];
    }

    public static function viewMenu()
    {
        $datas = [];
        foreach (self::menu() as $menu) {
            $secondMenus = [];

            if ($menu['type'] == 'single') {
                if (auth('backend')->user()->is_admin == 1 ||
                    array_intersect($menu['groups'], auth('backend')->user()->groupsArr)) {

                    $data = [];
                    $data['name'] = $menu['name'];
                    $data['icon'] = $menu['icon'];
                    $data['type'] = $menu['type'];
                    $data['url'] = $menu['url'];
                    $data['groups'] = $menu['groups'];
                    array_push($datas, $data);
                }
            } else {

                foreach ($menu['items'] as $item) {
                    if (auth('backend')->user()->is_admin == 1 ||
                        array_intersect($item['groups'], auth('backend')->user()->groupsArr)) {
                        array_push($secondMenus, $item);
                    }
                }

                if ($secondMenus) {
                    $data = [];
                    $data['name'] = $menu['name'];
                    $data['icon'] = $menu['icon'];
                    $data['type'] = $menu['type'];
                    $data['items'] = $secondMenus;
                    array_push($datas, $data);
                }
            }

        }

        return $datas;
    }

}
