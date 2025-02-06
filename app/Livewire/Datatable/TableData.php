<?php

namespace App\Livewire\Datatable;

use Livewire\Component;

class TableData extends Component
{
    public $label;
    public function render()
    {
        return view('livewire.datatable.table-data');
    }
}
