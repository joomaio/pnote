<?php

namespace App\plugins\note_html\registers;

use SPT\Application\IApp;

class Notetype
{
    public static function registerType()
    {
        return [
            'html' => [
                'namespace' => 'App\plugins\note_html\\',
                'title' => 'Html'
            ]
        ];
    }
}
