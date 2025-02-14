<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class Modal extends Component
{
    public Bool $isOpened = false;
    public String $title = '';
    public String $childComponent = '';
    public $componentData = [];

    public function render()
    {
        return view('livewire.modal');
    }

    #[On('toggle-modal')]
    public function toggle($title = '', $childComponent = '', $userData = null)
    {
        $this->isOpened = !$this->isOpened;
        $this->title = $title;
        $this->childComponent = $childComponent;
        $this->componentData = $userData;
    }
}
