<?php
namespace App\plugins\pnote\note_upload\registers;

use SPT\Application\IApp;
use App\plugins\pnote\note_upload\entities\FileEntity;

class Installer
{
    public static function info()
    {
        return ['tags'=>['pnote']];
    }
    
    public static function name()
    {
        return 'Plugin note upload';
    }

    public static function detail()
    {
        return [
            'author' => 'Pham Minh',
            'created_at' => '2023-01-03',
            'description' => 'Plugin note upload'
        ];
    }

    public static function version()
    {
        return '0.0.1';
    }

    public static function install( IApp $app)
    {
        // DB Entity
        $container = $app->getContainer();
        $FileEntity = new FileEntity($container->get('query'));
        $try = $FileEntity->checkAvailability();
        
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