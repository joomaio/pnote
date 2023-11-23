<?php
namespace App\plugins\share_note\models;

use SPT\Container\Client as Base;

class PermissionShareModel extends Base
{
    private $groups;

    public function checkPermission($share_user_group)
    {
        if (!$this->groups)
        {
            $this->groups = $this->UserEntity->getGroups($this->user->get('id'));
        }

        if(!is_array($share_user_group))
        {
            $share_user_group = $this->ShareGroupModel->convert($share_user_group, false);
        }

        foreach($this->groups as $group)
        {
            if(in_array($group['group_id'], $share_user_group))
            {
                return true;
            }
        }

        return false;
    }
}