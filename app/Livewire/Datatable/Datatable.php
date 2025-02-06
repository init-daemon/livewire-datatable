<?php

namespace App\Livewire\Datatable;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class Datatable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $data;
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $showFilters = false;
    public $filters = [
        'status' => '',
        'created_at_from' => '',
        'created_at_to' => '',
        'balance_min' => '',
        'balance_max' => '',
    ];
    public $selectedColumns = [
        'user_id' => true,
        'name' => true,
        'phone' => true,
        'description' => true,
        'status' => true,
        'balance' => true,
        'deposit' => true,
        'created_at' => true,
    ];

    public function mount($data = [])
    {
        $this->data = collect($data);
    }

    public function getColumns()
    {
        return [
            'user_id' => 'ID',
            'name' => 'Nom',
            'phone' => 'Téléphone',
            'description' => 'Email',
            'status' => 'Statut',
            'balance' => 'Solde',
            'deposit' => 'Dépôt',
            'created_at' => 'Date création'
        ];
    }

    #[Computed]
    public function rows()
    {
        $collection = collect($this->data)
            ->when($this->search, function ($collection) {
                return $collection->filter(function ($row) {
                    return str_contains(strtolower($row['name']), strtolower($this->search)) ||
                        str_contains(strtolower($row['phone']), strtolower($this->search)) ||
                        str_contains(strtolower($row['description']), strtolower($this->search));
                });
            });

        return new LengthAwarePaginator(
            $collection->forPage($this->getPage(), $this->perPage),
            $collection->count(),
            $this->perPage,
            $this->getPage(),
            [
                'path' => \Illuminate\Support\Facades\URL::current(),
                'pageName' => 'page',
            ]
        );
    }

    protected function getPage()
    {
        return $this->paginators['page'] ?? 1;
    }

    public function render()
    {
        return view('livewire.datatable.datatable');
    }
}
