<?php
namespace App\plugins\shortcut\models;

use SPT\Container\Client as Base;
use SPT\Traits\ErrorString;

class ShortcutModel extends Base
{ 
    use ErrorString; 

    public function replaceLink($link, $encode = true)
    {
        $replace = $encode ? '_sdm_app_domain_' : $this->router->url();
        $search = $encode ? $this->router->url() : '_sdm_app_domain_';
        
        $link = str_replace($search, $replace, $link);

        return $link;
    }

    public function remove($id)
    {
        if (!$id)
        {
            return false;
        }

        $try = $this->ShortcutEntity->remove($id);
        return $try;
    }
    
    public function add($data)
    {
        $data['link'] = isset($data['link']) ? $this->replaceLink($data['link']) : '';
        $data = $this->ShortcutEntity->bind($data);

        if (!$data || !isset($data['readyNew']) || !$data['readyNew'])
        {
            $this->error = $this->ShortcutEntity->getError();
            return false;
        }

        $newId =  $this->ShortcutEntity->add($data);

        if (!$newId)
        {
            $this->error = $this->ShortcutEntity->getError();
            return false;
        }

        return $newId;
    }

    public function update($data)
    {
        $data = $this->ShortcutEntity->bind($data);
        $data['link'] = isset($data['link']) ? $this->replaceLink($data['link']) : '';

        if (!$data || !isset($data['readyUpdate']) || !$data['readyUpdate'])
        {
            $this->error = $this->ShortcutEntity->getError();
            return false;
        }

        $try = $this->ShortcutEntity->update($data);
        if (!$try)
        {
            $this->error = $this->ShortcutEntity->getError();
            return false;
        }

        return $try;
    }

    public function getShortcut()
    {
        $list = $this->ShortcutEntity->list(0, 0,['user_id' => $this->user->get('id')]);
        $shortcuts = [];
        $groups = [];
        if ($list)
        {
            foreach($list as $item)
            {
                $item['link'] = $this->replaceLink($item['link'], false);
                if ($item['group'])
                {
                    if (isset($groups[$item['group']]))
                    {
                        $groups[$item['group']][] = $item;
                    }
                    else
                    {
                        $groups[$item['group']] = [$item];
                    }
                }
                else
                {
                    $shortcuts[] = $item;
                }
            }

            foreach($groups as $key => $value)
            {
                $shortcuts[] = [
                    'group' => $key,
                    'childs' => $value
                ];
            }
        }

        return $shortcuts;
    }
}
