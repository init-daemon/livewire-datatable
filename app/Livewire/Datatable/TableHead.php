<?php

namespace App\Livewire\Datatable;

use Livewire\Component;

class TableHead extends Component
{
    public string $label = '';
    public string $field = '';
    public string $sortField = '';
    public string $sortDirection = 'asc';
    public bool $sortable = true;

    public function render()
    {
        return view('livewire.datatable.table-head');
    }
}
