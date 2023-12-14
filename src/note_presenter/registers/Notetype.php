<?php

namespace App\plugins\pnote\note_presenter\registers;

use SPT\Application\IApp;

class Notetype
{
    public static function registerType()
    {
        return [
            'presenter' => [
                'namespace' => 'App\plugins\pnote\note_presenter\\',
                'title' => 'Presenter'
            ]
        ];
    }
}
