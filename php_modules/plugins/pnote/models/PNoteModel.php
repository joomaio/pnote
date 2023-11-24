<?php
namespace App\plugins\pnote\models;

use SPT\Container\Client as Base;
use SPT\Traits\ErrorString;

class PNoteModel extends Base
{ 
    use ErrorString; 
    public function countMyNote()
    {
        $list = $this->NoteEntity->list(0,0, ['created_by' => $this->user->get('id'), 'status <> -1']);
        return $this->NoteEntity->getListTotal();
    }

    public function countMyFilter()
    {
        $list = $this->FilterEntity->list(0, 0, ['user_id' => $this->user->get('id')]);
        return $this->FilterEntity->getListTotal();
    }

    public function countShare()
    {
        $where = ['created_by Not LIKE '. $this->user->get('id')];
        $where_permission = [];
        $where_permission[] = "(`share_user` LIKE '%(" . $this->user->get('id') . ")%')";

        $groups = $this->UserEntity->getGroups($this->user->get('id'));
        foreach($groups as $group)
        {
            $where_permission[] = "(`share_user_group` LIKE '%(" . $group['group_id'] . ")%')";
        }

        $where[] = '('. implode(" OR ", $where_permission) . ')';

        $list = $this->NoteEntity->list(0, 0, $where);
        return $this->NoteEntity->getListTotal();

    }
}
