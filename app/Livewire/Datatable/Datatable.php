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

    public function getStatusOptions()
    {
        return ['Active', 'Inactive', 'Pending'];
    }

    public function resetFilters()
    {
        $this->reset('filters');
        $this->resetPage();
    }

    public function updatedFilters()
    {
        $this->resetPage();
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
            })
            ->when($this->filters['status'], function ($collection) {
                return $collection->filter(fn($row) => $row['status'] === $this->filters['status']);
            })
            ->when($this->filters['created_at_from'], function ($collection) {
                return $collection->filter(fn($row) => Carbon::parse($row['created_at'])->greaterThanOrEqualTo($this->filters['created_at_from']));
            })
            ->when($this->filters['created_at_to'], function ($collection) {
                return $collection->filter(fn($row) => Carbon::parse($row['created_at'])->lessThanOrEqualTo($this->filters['created_at_to']));
            })
            ->when($this->filters['balance_min'], function ($collection) {
                return $collection->filter(fn($row) => $row['balance'] >= $this->filters['balance_min']);
            })
            ->when($this->filters['balance_max'], function ($collection) {
                return $collection->filter(fn($row) => $row['balance'] <= $this->filters['balance_max']);
            })
            ->when($this->sortField, function ($collection) {
                return $collection->sortBy(
                    $this->sortField,
                    SORT_REGULAR,
                    $this->sortDirection === 'desc'
                );
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

    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sort($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleColumn($column)
    {
        $this->selectedColumns[$column] = !$this->selectedColumns[$column];
    }

    public function selectAllColumns()
    {
        $this->selectedColumns = array_map(function () {
            return true;
        }, $this->getColumns());
    }

    public function deselectAllColumns()
    {
        $this->selectedColumns = array_map(function () {
            return false;
        }, $this->getColumns());
    }

    public function render()
    {
        return view('livewire.datatable.datatable');
    }
}
