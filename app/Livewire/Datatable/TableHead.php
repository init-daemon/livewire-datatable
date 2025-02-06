<?php 
namespace App\Livewire\Datatable;

use Livewire\Component;

class TableHead extends Component
{
    public string $label = '';

    public function render()
    {
        return view('livewire.datatable.table-head');
    }
}
