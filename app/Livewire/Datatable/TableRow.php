<?php

namespace App\Livewire\Datatable;

use Livewire\Component;

class TableRow extends Component
{
    public $row;
    public $columns;

    public function mount($row, $columns)
    {
        $this->row = $row;
        $this->columns = $columns;
    }

    public function render()
    {
        return view('livewire.datatable.table-row');
    }
}
