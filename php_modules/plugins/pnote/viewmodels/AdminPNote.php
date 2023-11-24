<?php
namespace App\plugins\pnote\viewmodels;

use SPT\Web\Gui\Form;
use SPT\Web\Gui\Listing;
use SPT\Web\ViewModel;

class AdminPNote extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>[
                'pnote.dashboard',
            ]
        ];
    }

    public function dashboard()
    {
        $shortcuts = $this->ShortcutModel->getShortcut();
        $countMyNote = $this->PNoteModel->countMyNote();
        $countMyFilter = $this->PNoteModel->countMyFilter();
        $countShare = $this->PNoteModel->countShare();

        return [
            'url' => $this->router->url(),
            'shortcuts' => $shortcuts,
            'countShare' => $countShare,
            'countMyNote' => $countMyNote,
            'countMyFilter' => $countMyFilter,
            'link_shortcut_form' => $this->router->url('shortcut'),
            'link_shortcut_list' => $this->router->url('shortcuts'),
        ];
    }
}
