<?php
namespace App\pnote\pnote\registers;

use SPT\Application\IApp;

class Installer
{
    public static function info()
    {
        return [
            'tags'=>['sdm'],
            'type' => 'plugin',
            'solution' => 'pnote',
            'folder_name' => 'pnote',
            'name' => 'Plugin pnote',
            'require' => []
        ];
    }
    
    public static function name()
    {
        return 'Plugin PNote';
    }

    public static function detail()
    {
        return [
            'author' => 'Pham Minh',
            'created_at' => '2023-01-03',
            'description' => 'Plugin used to demo how the SPT works'
        ];
    }

    public static function version()
    {
        return '0.0.1';
    }

    public static function registerButton(IApp $app)
    {
        $container = $app->getContainer();
        $container->get('PermissionModel');
        $super_user_groups = [];
        $user_groups = $container->get('GroupEntity')->list(0, 0, []);
        foreach($user_groups as $group)
        {
            if (str_contains($group['access'], 'user_manager'))
            {
                $super_user_groups[] = $group['id'];
            }
        }

        $super_users = $container->get('UserGroupEntity')->list(0, 0, ['group_id IN (' . implode(',', $super_user_groups) . ')']);

        if (count($super_user_groups) == 0 || count($super_users) == 0) {
            return [
                'button-name' => 'Create User',
                'button-class' => 'btn btn-primary',
                'button-id' => 'create-user-button',
                'button-style' => 'margin-right: 3px;',
                'button-modal-info' => 'data-bs-toggle="modal" data-bs-target="#createSuperUser"',
                'modal-widget' => 'pnote::createSuperUser'
            ];
        }

        return false;
        
    }

    public static function install( IApp $app)
    {
        // run sth to prepare the install
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