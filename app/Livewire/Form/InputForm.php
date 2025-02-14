<?php
namespace App\Livewire\Form;

use Livewire\Attributes\Modelable;
use Livewire\Component;

class InputForm extends Component
{
    #[Modelable]
    public $value = ''; 
    public $placeholder = '';

    public function mount($value = '')
    {
        $this->value = $value;
    }

    public function render()
    {
        return view('livewire.form.input-form');
    }
}
