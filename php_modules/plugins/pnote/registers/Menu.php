<?php
namespace App\plugins\pnote\registers;

use SPT\Application\IApp;
use SPT\Support\Loader;

class Menu
{
    public static function registerItem( IApp $app )
    {
        $container = $app->getContainer();
        $router = $container->get('router');
        $path_current = $router->get('actualPath');

        $active = $path_current == '/' ? 'active' : '';
        $menu = [
            [
                'link' => $router->url(''),
                'title' => 'Dashboard', 
                'icon' => '<i class="fa-solid fa-gauge"></i>',
                'class' => $active,
            ]
        ];
        
        return [
            'menu' => $menu,
            'order' => 0,
        ];
    }
}