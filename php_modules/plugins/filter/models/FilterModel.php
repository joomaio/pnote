<?php
namespace App\plugins\filter\models;

use SPT\Container\Client as Base;
use SPT\Traits\ErrorString;

class FilterModel extends Base
{ 
    use ErrorString; 

    public function remove($id)
    {
        if (!$id)
        {
            return false;
        }

        $find = $this->FilterEntitty->findByPK($id);
        if (!$find)
        {
            $this->error = 'Invalid Filter';
            return false;
        }

        $try = $this->FilterEntity->remove($id);
        if ($try)
        {
            // remove Shortcut
            if ($find['shortcut_id'])
            {
                $this->ShortcutModel->remove($find['shortcut_id']);
            }
        }

        return $try;
    }
    
    public function add($data)
    {
        $data = $this->FilterEntity->bind($data);

        if (!$data || !isset($data['readyNew']) || !$data['readyNew'])
        {
            $this->error = $this->FilterEntity->getError();
            return false;
        }

        $newId =  $this->FilterEntity->add($data);

        if (!$newId)
        {
            $this->error = $this->FilterEntity->getError();
            return false;
        }

        return $newId;
    }

    public function update($data)
    {
        $data['link'] = isset($data['link']) ? $this->replaceLink($data['link']) : '';

        if (!$data || !isset($data['readyUpdate']) || !$data['readyUpdate'])
        {
            $this->error = $this->FilterEntity->getError();
            return false;
        }

        $try = $this->FilterEntity->update($data);
        if (!$try)
        {
            $this->error = $this->FilterEntity->getError();
            return false;
        }

        return $try;
    }
}
