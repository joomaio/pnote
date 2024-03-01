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
            'dependencies' => []
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

    public static function afterInstall(IApp $app, $is_cli)
    {
        if ($is_cli)
        {
            $container = $app->getContainer();
            $super_user_groups = [];
            $user_groups = $container->get('GroupEntity')->list(0, 0, []);
            foreach ($user_groups as $group) {
                if (str_contains($group['access'], 'user_manager')) {
                    $super_user_groups[] = $group['id'];
                }
            }

            if (count($super_user_groups) == 0) {
                $access = $container->get('PermissionModel')->getAccess();

                // Create group
                $group = [
                    'name' => 'Super',
                    'description' => 'Super Group',
                    'access' => json_encode($access),
                    'status' => 1,
                    'created_by' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'modified_by' => 0,
                    'modified_at' => date('Y-m-d H:i:s')
                ];

                $created_group = $container->get('GroupEntity')->add($group);

                if (!$created_group) {
                    echo 'Create Group Failed';
                    return false;
                }
            }

            $super_users = $container->get('UserGroupEntity')->list(0, 0, ['group_id IN (' . implode(',', $super_user_groups) . ')']);

            if (count($super_user_groups) == 0 || count($super_users) == 0) {
                echo "Plugin Pnote requires to create super admin. \n";
                $enter_info = true;
                while ($enter_info) {
                    $name = readline("Enter your name:\n");
                    $username = readline("Enter your username:\n");
                    $email = readline("Enter your email:\n");
                    $password = readline("Enter your password (At least 6 characters):\n");

                    $user = [
                        'username' => $username,
                        'name' => $name,
                        'email' => $email,
                        'status' => 1,
                        'password' => $password,
                        'confirm_password' => $password,
                        'created_by' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'modified_by' => 0,
                        'modified_at' => date('Y-m-d H:i:s')
                    ];

                    $validate = $container->get('UserEntity')->validate($user);
                    if (!$validate) {
                        echo "Information you entered is invalid. Please enter again! \n";
                    } else {
                        $enter_info = false;
                    }
                }

                $created_user = $container->get('UserEntity')->add($user);
                if (!$created_user) {
                    echo 'Create User Failed';
                    return false;
                }

                if (count($super_user_groups) == 0) {
                    $group_id = $created_group;
                } else {
                    $group_id = $super_user_groups[0];
                }

                $created_user_group = $container->get('UserGroupEntity')->add([
                    'group_id' => $group_id,
                    'user_id' => $created_user,
                ]);

                if (!$created_user_group) {
                    echo  'Create User Group Failed';
                    return false;
                }

            }
        }
        
        return true;
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