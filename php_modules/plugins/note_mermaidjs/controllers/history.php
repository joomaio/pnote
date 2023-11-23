<?php
namespace App\plugins\note_mermaidjs\controllers;

use SPT\Response;
use DTM\note\libraries\NoteController;

class history extends NoteController
{
    public function detail()
    {
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];

        $exist = $this->HistoryEntity->findByPK($id);

        if(!empty($id) && !$exist)
        {
            $this->session->set('flashMsg', "Invalid note");
            return $this->app->redirect(
                $this->router->url('my-notes')
            );
        }

        $this->app->set('layout', 'backend.history');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
    }

    public function rollback()
    {
        $id = $this->validateID();
        $try = $this->NoteMermaidModel->rollback($id);

        $msg = $try ? 'Rollback Successfully' : $this->NoteMermaidModel->getError();

        $link = $try ? 'note/detail/'. $try : 'history/note-html/'.$id;
    
        $this->session->set('flashMsg', $msg);
        return $this->app->redirect(
            $this->router->url($link)
        );
    }

    public function validateID()
    {
        
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];

        if(empty($id))
        {
            $ids = $this->request->post->get('ids', [], 'array');
            if(count($ids)) return $ids;

            $this->session->set('flashMsg', 'Invalid version');
            return $this->app->redirect(
                $this->router->url('my-notes'),
            );
        }

        return $id;
    }
}