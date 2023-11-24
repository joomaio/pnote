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
            'link_form_filter' => $this->router->url('my-filter/edit/0'),
            'link_filter' => $this->router->url('my-filters'),
            'link_mynote' => $this->router->url('my-notes'),
            'link_sharenote' => $this->router->url('share-notes'),
            'countShare' => $countShare,
            'countMyNote' => $countMyNote,
            'countMyFilter' => $countMyFilter,
            'link_shortcut_form' => $this->router->url('shortcut'),
            'link_shortcut_list' => $this->router->url('shortcuts'),
        ];
    }
}
