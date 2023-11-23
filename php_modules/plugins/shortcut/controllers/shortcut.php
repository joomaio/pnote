<?php
namespace App\plugins\shortcut\controllers;

use SPT\Response;
use SPT\Web\ControllerMVVM;

class shortcut extends ControllerMVVM
{
    public function list()
    {
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
        $this->app->set('layout', 'backend.note.list');
    }

    public function delete()
    {
        $ids = $this->validateID();

        $count = 0;
        if( is_array($ids))
        {
            foreach($ids as $id)
            {
                //Delete file in source
                if( $this->ShortcutModel->remove( $id ) )
                {
                    $count++;
                }
            }
        }
        elseif( is_numeric($ids) )
        {
            if( $this->ShortcutModel->remove($ids ) )
            {
                $count++;
            }
        }


        $this->session->set('flashMsg', $count.' deleted record(s)');
        return $this->app->redirect(
            $this->router->url('my-notes'),
        );
    }

    public function validateID()
    {
        
        $urlVars = $this->request->get('urlVars');
        $id = $urlVars ? (int) $urlVars['id'] : 0;

        if(empty($id))
        {
            $ids = $this->request->post->get('ids', [], 'array');
            if(count($ids)) return $ids;

            $this->session->set('flashMsg', 'Invalid note');
            return $this->app->redirect(
                $this->router->url('my-notes'),
            );
        }

        return $id;
    }

    public function search()
    {
        $search = trim($this->request->get->get('search', '', 'string'));
        $type = trim($this->request->get->get('type', '', 'string'));
        $ignore = $this->request->get->get('ignore', '', 'string');
        
        $list = $this->NoteModel->searchAjax($search, $ignore, $type);

        $this->app->set('format', 'json');
        $this->set('status' , 'success');
        $this->set('data' , $list);
        $this->set('message' , '');
        return;
    }

    public function request()
    {
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];
       
        $list = $this->NoteModel->getRequest($id);
        
        $this->app->set('format', 'json');
        $this->set('status' , 'success');
        $this->set('data' , $list);
        $this->set('message' , '');
        return;
    }
}