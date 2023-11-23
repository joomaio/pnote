<?php

/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: A simple View Model
 * 
 */

namespace App\plugins\share_note\viewmodels;

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
        $share_user = isset($data['share_user']) ? $data['share_user'] : '';

        $share_user = $this->ShareUserModel->convert($share_user, false);
        if (!$share_user)
        {
            $share_user = [];
        }

        $users = $this->UserEntity->list(0, 0, []);
        $user_groups = $this->GroupEntity->list(0,0, []);
        

        $data = isset($viewData['data']) ? $viewData['data'] : [];
        $share_user_group = isset($data['share_user_group']) ? $data['share_user_group'] : '';
        $share_user_group = $this->ShareGroupModel->convert($share_user_group, false);
        if (!$share_user_group)
        {
            $share_user_group = [];
        }

        return [
            'share_user' => $share_user,
            'share_user_group' => $share_user_group,
            'users' => $users,
            'user_groups' => $user_groups,
        ];
    }
}
