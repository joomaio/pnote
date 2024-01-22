<?php

/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: A simple View Model
 * 
 */

namespace App\pnote\share_note\viewmodels;

use SPT\Web\ViewModel;
use SPT\Web\Gui\Form;

class ShareNote extends ViewModel
{
    public static function register()
    {
        return [
            'widget'=>[
                'backend.javascript',
                'backend.share_note',
            ],
        ];
    }
    
    public function javascript()
    {
        return [
            'link_assignee' => $this->router->url('user/search'),
        ];
    }

    public function share_note($layoutData, $viewData)
    {
        $data = isset($viewData['data']) ? $viewData['data'] : [];
        $assign_user = isset($data['assignee']) ? $data['assignee'] : '';

        $assign_user = $this->ShareUserModel->convert($assign_user, false);
        if (!$assign_user)
        {
            $assign_user = [];
        }

        $user_access = $this->PermissionModel->getAccessByUser();

        if (in_array('user_manager', $user_access)) {
            $users = $this->UserEntity->list(0, 0, []);
            $user_groups = $this->GroupEntity->list(0,0, []);
        } else {
            $group_ids = $this->UserGroupEntity->list(0,0,['user_id = ' . $this->user->get('id')]);
            $group_id_arr = '(';
            foreach ($group_ids as $idx => $group) {
                if ($idx != 0) {
                    $group_id_arr .= ',';
                }
                $group_id_arr .= $group['group_id'];
            }
            $group_id_arr .= ')';
            $user_groups = $this->GroupEntity->list(0,0, ['id IN ' . $group_id_arr]);

            $user_ids = $group_ids = $this->UserGroupEntity->list(0,0,['group_id IN ' . $group_id_arr]);
            $user_id_arr = '(';
            if (is_array($user_ids)) {
                foreach ($user_ids as $idx => $user) {
                    if ($idx != 0) {
                        $user_id_arr .= ',';
                    }
                    $user_id_arr .= $user['user_id'];
                }
            }
            $user_id_arr .= ')';
            $users = $this->UserEntity->list(0, 0, ['id IN ' . $user_id_arr]);
        }

        $assign_user_group = isset($data['assign_user_group']) ? $data['assign_user_group'] : '';
        $assign_user_group = $this->ShareGroupModel->convert($assign_user_group, false);
        if (!$assign_user_group)
        {
            $assign_user_group = [];
        }

        $filter_name = $this->request->get->get('filter', '');
        if($filter_name)
        {
            $filter = $this->CollectionModel->checkFilterName($filter_name);
            $assignment = $this->CollectionModel->convertArray($filter['assignment'], false);
            $user_tmp = $group_tmp = [];
            foreach($assignment as $item)
            {
                $tmp = explode('-', $item);
                $id = end($tmp);
                if(strpos($item, 'user') !== false)
                {
                    $user_tmp[] = $id;
                }
                else
                {
                    $group_tmp[] = $id;
                }
            }

            $assign_user = $assign_user ? $assign_user : $user_tmp;
            $assign_user_group = $assign_user_group ? $assign_user_group : $group_tmp;
        }

        return [
            'assign_user' => $assign_user,
            'assign_user_group' => $assign_user_group,
            'users' => $users,
            'user_groups' => $user_groups,
        ];
    }
}
