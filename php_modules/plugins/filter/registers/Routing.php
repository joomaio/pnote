<?php

namespace App\plugins\filter\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'my-filters' => [
                'fnc' => [
                    'get' => 'filter.filter.list',
                ],
            ],
            'my-filter/edit' => [
                'fnc' => [
                    'get' => 'filter.filter.detail',
                    'post' => 'filter.filter.add',
                    'put' => 'filter.filter.update',
                    'delete' => 'filter.filter.delete',
                ],
                'parameters' => ['id'],
            ],
            'my-filter' => [
                'fnc' => [
                    'get' => 'filter.filter.filter',
                    'post' => 'filter.filter.filter',
                ],
                'parameters' => ['filter-name'],
            ],
        ];
    }
}
