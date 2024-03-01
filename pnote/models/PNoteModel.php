<?php
namespace App\pnote\pnote\models;

use SPT\Container\Client as Base;
use SPT\Traits\ErrorString;

class PNoteModel extends Base
{ 
    use ErrorString; 
    public function countMyNote()
    {
        $list = $this->NoteEntity->list(0,0, ['created_by' => $this->user->get('id'), 'status > -1']);
        return $this->NoteEntity->getListTotal();
    }

    public function countMyFilter()
    {
        $list = $this->CollectionEntity->list(0, 0, ['user_id' => $this->user->get('id')]);
        return $this->CollectionEntity->getListTotal();
    }

    public function countMyShared()
    {
        $where = ['created_by Not LIKE '. $this->user->get('id'), 'status > -1', '(assign_user <> "" OR assign_user_group <> "")'];

        $list = $this->NoteEntity->list(0,0, $where);
        return $this->NoteEntity->getListTotal();
    }

    public function countShare()
    {
        $where = ['created_by Not LIKE '. $this->user->get('id'), 'status > -1'];
        $where_permission = [];
        $where_permission[] = "(`assign_user` LIKE '%(" . $this->user->get('id') . ")%')";

        $groups = $this->UserEntity->getGroups($this->user->get('id'));
        foreach($groups as $group)
        {
            $where_permission[] = "(`assign_user_group` LIKE '%(" . $group['group_id'] . ")%')";
        }

        $where[] = '('. implode(" OR ", $where_permission) . ')';

        $list = $this->NoteEntity->list(0, 0, $where);
        return $this->NoteEntity->getListTotal();

    }

    public function createSuperUser($data)
    {
        $result = array(
            'success' => false,
            'message' => '',
        );

        $super_user_groups = [];
        $user_groups = $this->GroupEntity->list(0, 0, []);
        foreach($user_groups as $group)
        {
            if (str_contains($group['access'], 'user_manager'))
            {
                $super_user_groups[] = $group['id'];
            }
        }

        if (count($super_user_groups) == 0) {
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
    
            $created_group = $this->GroupEntity->add($group);

        
            if (!$created_group)
            {
                $result['message'] = 'Create Group Failed';
                return $result;
            }
        }

        $super_users = $this->UserGroupEntity->list(0, 0, ['group_id IN (' . implode(',', $super_user_groups) . ')']);

        if (count($super_user_groups) == 0 || count($super_users) == 0)
        {
            $id = isset($data['id']) ? $data['id'] : 0;
            $password = isset($data['password']) ? $data['password'] : '';
            $confirm_password = isset($data['confirm_password']) ? $data['confirm_password'] : '';
            $username = $data['username'];
            $email = $data['email'];
            $name = $data['name'];

            $id = isset($data['id']) ? $data['id'] : 0;
            if(!empty($password)) 
            {
                if (strlen($password) < '6') 
                {
                    $result['message'] = "Your Password Must Contain At Least 6 Characters!";
                    return $result;
                }

                if ($password != $confirm_password)
                {
                    $result['message'] = 'Confirm Password Invalid';
                    return $result;
                }
            } 
            else 
            {
                $result['message'] = "Passwords can't empty";
                return $result;
            }

            // validate user name
            if(!empty($username)) 
            {
                $find = $this->UserEntity->findOne(['username' => $username]);
                if ($find && $find['id'] != $id)
                {
                    $result['message'] = "Username already exists";
                    return $result;
                }
            } 
            else 
            {
                $result['message'] = "UserName cant't empty";
                return $result;
            }

            //validate email
            if(!empty($email)) {
                $findEmail = $this->UserEntity->findOne(['email' => $email]);
                if ($findEmail && $findEmail['id'] != $id)
                {
                    $result['message'] = "Email already exists";
                    return $result;
                }
            } else {
                $result['message'] = "Email can't empty";
                return $result;
            }

            $user = [
                'username' => $username,
                'name' => $name,
                'email' => $email,
                'status' => 1,
                'password' =>  $password,
                'confirm_password' =>  $confirm_password,
                'created_by' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'modified_by' => 0,
                'modified_at' => date('Y-m-d H:i:s')
            ];

            $created_user = $this->UserEntity->add($user);
            if (!$created_user)
            {
                $result['message'] = 'Create User Failed';
                return $result;
            }

            if (count($super_user_groups) == 0) {
                $group_id = $created_group;
            } else {
                $group_id = $super_user_groups[0];
            }
    
            $created_user_group = $this->UserGroupEntity->add([
                'group_id' => $group_id,
                'user_id' => $created_user,
            ]); 
    
            if (!$created_user_group)
            {
                $result['message'] = 'Create User Group Failed';
                return $result;
            }
        }

        // Create filter my notes and my shares
        $shortcut = $this->createShortcut($created_user);
        if (!$shortcut['success'])
        {
            $result['message'] = $shortcut['message'];
            return $result;
        }

        $filter = $this->createFilter($created_user, $shortcut['my_notes_id'], $shortcut['my_shares_id']);
        if (!$filter['success'])
        {
            $result['message'] = $filter['message'];
            return $result;
        }

        $result['success'] = true;
        $result['message'] = 'Create Super User Successfully';
        return $result;
    }

    public function createFilter($user_id, $my_notes_id, $my_shares_id)
    {
        $result = array(
            'success' => false,
            'message' => '',
        );

        $try = $this->CollectionModel->add([
            'user_id' => $user_id,
            'shortcut_id' => $my_notes_id,
            'name' => 'My Notes',
            'select_object' => 'note',
            'start_date' => '',
            'end_date' => '',
            'tags' => '',
            'filters' => [],
            'shares' => [],
            'creator' => [1],
            'assignment' => [],
            'shortcut_name' => '',
            'shortcut_link' => '',
            'shortcut_group' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $user_id,
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $user_id,
        ], false);

        if (!$try)
        {
            $result['message'] = 'Create Filter My Notes Failed';
            return $result;
        }

        $try = $this->CollectionModel->add([
            'user_id' => $user_id,
            'shortcut_id' => $my_shares_id,
            'name' => 'My Shares',
            'select_object' => 'note',
            'start_date' => '',
            'end_date' => '',
            'tags' => '',
            'creator' => [],
            'filters' => [],
            'shares' => [],
            'assignment' => ['user-1'],
            'shortcut_name' => '',
            'shortcut_link' => '',
            'shortcut_group' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $user_id,
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $user_id,
        ], false); 

        if (!$try)
        {
            $result['message'] = 'Create Filter My Shares Failed';
            return $result;
        }

        $result['message'] = 'Create Filters Successfully';
        $result['success'] = true;
        return $result;
    }

    public function createShortcut($user_id)
    {
        $result = array(
            'success' => false,
            'message' => '',
            'my_notes_id' => 0,
            'my_shares_id' => 0
        );

        $my_notes_id = $this->ShortcutEntity->add([
            'name' => 'My Notes',
            'link' => '_sdm_app_domain_collection/my-notes',
            'group' => '',
            'user_id' => $user_id,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $user_id,
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $user_id,
        ]); 

        if (!$my_notes_id)
        {
            $result['message'] = 'Create Filter My Notes Failed';
            return $result;
        }

        $my_shares_id = $this->ShortcutEntity->add([
            'name' => 'My Shares',
            'link' => '_sdm_app_domain_collection/my-shares',
            'group' => '',
            'user_id' => $user_id,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $user_id,
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $user_id,
        ]); 

        if (!$my_shares_id)
        {
            $result['message'] = 'Create Filter My Shares Failed';
            return $result;
        }

        $result['message'] = 'Create Filters Successfully';
        $result['success'] = true;
        $result['my_notes_id'] = $my_notes_id;
        $result['my_shares_id'] = $my_shares_id;
        return $result;
    }
}
