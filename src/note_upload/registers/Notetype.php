<?php

namespace App\plugins\note_upload\registers;

use SPT\Application\IApp;

class Notetype
{
    public static function registerType()
    {
        return [
            'upload' => [
                'namespace' => 'App\plugins\note_upload\\',
                'model' => 'NoteFileModel',
                'title' => 'Upload'
            ]
        ];
    }
}
