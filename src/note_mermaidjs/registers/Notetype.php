<?php

namespace App\plugins\note_mermaidjs\registers;

use SPT\Application\IApp;

class Notetype
{
    public static function registerType()
    {
        return [
            'mermaidjs' => [
                'namespace' => 'App\plugins\note_mermaidjs\\',
                'title' => 'Mermaid Js'
            ]
        ];
    }
}
