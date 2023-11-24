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

        $find = $this->FilterEntity->findByPK($id);
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
    
    public function convertArray($data, $encode = true)
    {
        if ($encode)
        {
            if (is_array($data))
            {
                $data = implode('),(', $data);
                $data = $data ? '('. $data .')' : '';
            }
        }
        else
        {
            if(is_string($data))
            {
                $data = str_replace(['(', ')'], '', $data);
                $data = explode(',', $data);
            }
        }

        return $data;
    }
    public function add($data)
    {
        $data['tags'] = $data['tags'] ? $this->convertArray($data['tags']) : '';;
        $data['creator'] = $data['creator'] ? $this->convertArray($data['creator']) : '';;
        $data['permission'] = $data['permission'] ? $this->convertArray($data['permission']) : '';;
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
        $data['tags'] = $data['tags'] ? $this->convertArray($data['tags']) : '';;
        $data['creator'] = $data['creator'] ? $this->convertArray($data['creator']) : '';;
        $data['permission'] = $data['permission'] ? $this->convertArray($data['permission']) : '';;
        $data = $this->FilterEntity->bind($data);

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

    public function getDetail($id)
    {
        if(!$id)
        {
            return [];
        }

        $data = $this->FilterEntity->findByPK($id);
        if ($data)
        {
            $data['start_date'] = $data['start_date'] ? date('Y-m-d', strtotime($data['start_date'])) : '';
            $data['end_date'] = $data['end_date'] ? date('Y-m-d', strtotime($data['end_date'])) : '';
        }

        $data['tags'] = $data['tags'] ? $this->convertArray($data['tags'], false) : [];
        $data['creator'] = $data['creator'] ? $this->convertArray($data['creator'], false) : [];
        $data['permission'] = $data['permission'] ? $this->convertArray($data['permission'], false) : [];

        return $data;
    }

    public function checkFilterName($filter_name)
    {
        if (!$filter_name)
        {
            return false;
        }
        
        $filter_name = strtolower(urldecode($filter_name));
        $where = ['LOWER(name) LIKE "'.$filter_name.'"'];
        $where[] = ['user_id' => $this->user->get('id')];
        $findOne = $this->FilterEntity->findOne($where);
        
        if($findOne)
        {
            return $findOne;
        }

        return false;
    }
}
