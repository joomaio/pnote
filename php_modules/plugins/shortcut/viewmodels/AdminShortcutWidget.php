<?php
namespace App\plugins\shortcut\viewmodels;

use SPT\Web\Gui\Form;
use SPT\Web\Gui\Listing;
use SPT\Web\ViewModel;

class AdminShortcutWidget extends ViewModel
{
    public static function register()
    {
        return [
            'widget'=>[
                'form',
            ],
        ];
    }

    public function form()
    {
        $form = new Form($this->getFields(), []);
        
        return [
            'form' => $form,
            'url' => $this->router->url(),
            'link_list' =>  '',
            'link_form' => $this->router->url('note/edit'),
            'link_preview' => $this->router->url('note/detail'),
            'token' => $this->token->value(),
        ];
    }

    public function getFields()
    {
        return [
            'name' => [
                'text',
                'default' => '',
                'showLabel' => false,
                'formClass' => 'form-control',
                'placeholder' => 'Name'
            ],
            'link' => [
                'text',
                'default' => '',
                'showLabel' => false,
                'formClass' => 'form-control',
                'placeholder' => 'Link'
            ],
            'group' => [
                'text',
                'default' => '',
                'showLabel' => false,
                'formClass' => 'form-control',
                'placeholder' => 'Group'
            ],
        ];
    }
}