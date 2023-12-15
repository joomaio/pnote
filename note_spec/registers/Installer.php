<?php
namespace App\plugins\pnote\note_spec\registers;

use SPT\Application\IApp;
use App\plugins\pnote\note_spec\entities\TreeNoteEntity;

class Installer
{
    public static function info()
    {
        return ['tags'=>['pnote']];
    }
    
    public static function name()
    {
        return 'Plugin note spec';
    }

    public static function detail()
    {
        return [
            'author' => 'Pham Minh',
            'created_at' => '2023-01-03',
            'description' => 'Plugin note spec'
        ];
    }

    public static function version()
    {
        return '0.0.1';
    }

    public static function assets()
    {
        return [
            'specs' => 'assets/specs',
        ];
    }

    public static function install( IApp $app)
    {
        // DB Entity
        $container = $app->getContainer();
        $TreeNoteEntity = new TreeNoteEntity($container->get('query'));
        $try = $TreeNoteEntity->checkAvailability();

        return true;
    }
    public static function uninstall( IApp $app)
    {
        // run sth to uninstall
    }
    public static function active( IApp $app)
    {
        // run sth to prepare the install
    }
    public static function deactive( IApp $app)
    {
        // run sth to uninstall
    }
}