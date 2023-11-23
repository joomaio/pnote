<?php
namespace App\plugins\shortcut\registers;

use SPT\Application\IApp;
use App\plugins\shortcut\libraries\NoteDispatch;

class Dispatcher
{
    public static function dispatch(IApp $app)
    {
        $app->plgLoad('permission', 'CheckSession'); 

        $noteDispatcher = new NoteDispatch($app->getContainer());
        $noteDispatcher->execute();
    }
}