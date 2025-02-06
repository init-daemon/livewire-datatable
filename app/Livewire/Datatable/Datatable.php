<?php

namespace App\Livewire\Datatable;

use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Str;

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

    public function getStatusOptions()
    {
        return ['Active', 'Inactive', 'Pending'];
    }

    public function getPage($pageName = 'page')
    {
        return $this->paginators[$pageName] ?? Paginator::resolveCurrentPage($pageName);
    }

    public function previousPage($pageName = 'page')
    {
        $this->setPage(max(($this->paginators[$pageName] ?? 1) - 1, 1), $pageName);
    }

    public function nextPage($pageName = 'page')
    {
        $this->setPage(($this->paginators[$pageName] ?? 1) + 1, $pageName);
    }

    public function gotoPage($page, $pageName = 'page')
    {
        $this->setPage($page, $pageName);
    }

    public function resetPage($pageName = 'page')
    {
        $this->setPage(1, $pageName);
    }

    public function setPage($page, $pageName = 'page')
    {
        if (is_numeric($page)) {
            $page = (int) ($page <= 0 ? 1 : $page);
        }

        $beforePaginatorMethod = 'updatingPaginators';
        $afterPaginatorMethod = 'updatedPaginators';

        $beforeMethod = 'updating' . ucfirst(Str::camel($pageName));
        $afterMethod = 'updated' . ucfirst(Str::camel($pageName));

        if (method_exists($this, $beforePaginatorMethod)) {
            $this->{$beforePaginatorMethod}($page, $pageName);
        }

        if (method_exists($this, $beforeMethod)) {
            $this->{$beforeMethod}($page, null);
        }

        $this->paginators[$pageName] = $page;

        if (method_exists($this, $afterPaginatorMethod)) {
            $this->{$afterPaginatorMethod}($page, $pageName);
        }

        if (method_exists($this, $afterMethod)) {
            $this->{$afterMethod}($page, null);
        }
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
