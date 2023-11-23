<?php

namespace App\plugins\db_tool\registers;

use SPT\Application\IApp;

class Cli
{
    public static function registerCommands()
    {
        return [
            'db-structure' => [
                'description' => 'Check availability database',
                'fnc' => 'db_tool.database.checkavailability'
            ],
            'install-data' => [
                'description' => 'Generate Data Sample',
                'fnc' => 'db_tool.database.generatedata'
            ]
        ];
    }
}
