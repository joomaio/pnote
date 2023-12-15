<?php

namespace App\plugins\pnote\note_upload\registers;

use SPT\Application\IApp;

class Notetype
{
    public static function registerType()
    {
        return [
            'upload' => [
                'namespace' => 'App\plugins\pnote\note_upload\\',
                'model' => 'NoteFileModel',
                'title' => 'Upload'
            ]
        ];
    }
}
