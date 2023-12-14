<?php

namespace App\plugins\pnote\note_spec\registers;

use SPT\Application\IApp;

class Notetype
{
    public static function registerType()
    {
        return [
            'spec' => [
                'namespace' => 'App\plugins\pnote\note_spec\\',
                'model' => 'NoteSpecModel',
                'title' => 'Spec'
            ]
        ];
    }
}
