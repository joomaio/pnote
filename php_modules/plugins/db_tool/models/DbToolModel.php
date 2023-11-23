<?php
/**
 * SPT software - Model
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic model
 * 
 */

namespace App\plugins\db_tool\models;

use SPT\Container\Client as Base;
use SPT\Support\Loader;

class DbToolModel extends Base
{ 
    use \SPT\Traits\ErrorString;

    private $entities;

    public function getEntities()
    {
        if ($this->entities)
        {
            return $this->entities;
        }

        $entities = [];
        $container = $this->getContainer();
        $plgList = $this->app->plugin(true);
        
        foreach($plgList as $plg)
        {
            Loader::findClass( 
                $plg['path']. '/entities', 
                $plg['namespace']. '\entities', 
                function($classname, $fullname) use ($container, &$entities)
                {
                    if ($container->exists($classname))
                    {
                        $entities[] = $classname;
                    }
            });
        }

        $this->entities = $entities;
        return $this->entities;
    }

    public function generate()
    {
        // Create data sample
        $user = [
            'username' => 'admin',
            'name' => 'Administrator',
            'email' => 'admin@g.com',
            'status' => 1,
            'password' => '123123',
            'confirm_password' => '123123',
            'created_by' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'modified_by' => 0,
            'modified_at' => date('Y-m-d H:i:s')
        ];

        $try = $this->UserEntity->add($user);
    
        if (!$try)
        {
            $this->error = 'Create User Failed';
            return false;
        }

        $access = $this->PermissionModel->getAccess();
        
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

        $try = $this->GroupEntity->add($group);
    
        if (!$try)
        {
            $this->error = 'Create Group Failed';
            return false;
        }

        $try = $this->UserGroupEntity->add([
            'group_id' => 1,
            'user_id' => 1,
        ]); 

        if (!$try)
        {
            $this->error = 'Create User Group Failed';
            return false;
        }

        return true;
    }

    public function truncate()
    {
        $entities = $this->getEntities();
        foreach($entities as $entity)
        {
            $try = $this->{$entity}->truncate();
            if ($try === false)
            {
                $this->error = $entity ." truncate failed";
                return false;
            }
        }

        return true;
    }

    public function setFolderUpload()
    {
        // check permission in folder media
        if (!file_exists(MEDIA_PATH))
        {
            if(!mkdir(MEDIA_PATH))
            {
                $this->error = "Can't create folder media";
                return false;
            }
        }
        if (is_readable(MEDIA_PATH) && is_writable(MEDIA_PATH) && is_executable(MEDIA_PATH))
        {
            return true;
        }

        if (!chmod(MEDIA_PATH, 755))
        {
            $this->error = "Can't change permission folder upload";
            return false;
        }

        return true;
    }
}
