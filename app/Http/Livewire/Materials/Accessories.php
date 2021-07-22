<?php

namespace App\Http\Livewire\Materials;

use App\Http\Livewire\Datatable\WithBulkActions;
use App\Http\Livewire\Datatable\WithCachedRows;
use App\Http\Livewire\Datatable\WithPerPagePagination;
use App\Http\Livewire\Datatable\WithSorting;
use App\Models\Accessory;
use App\Models\Provider;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Accessories extends Component
{
    use WithFileUploads, WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public string $pageTitle = 'Accessories';
    public bool $showAdvancedSearch = false;
    public Accessory $accessory;
    public $providers;

    protected $queryString = ['sortField', 'sortDirection', 'filters'];
    protected $listeners = ['refreshAccessories', '$refresh'];

    public array $filters = [
        'search'       => null,
        'quantity_min' => null,
        'quantity_max' => null,
        'amount_min'   => null,
        'amount_max'   => null,
        'height'       => null,
        'weight'       => null,
        'provider_id'  => null,
    ];


    public function rules(): array
    {
        return [
            'accessory.name'        => 'required|string|max:255',
            'accessory.height'      => 'nullable|string|max:255',
            'accessory.amount'      => 'required|numeric|max:99999999',
            'accessory.weight'      => 'nullable|string|max:255',
            'accessory.quantity'    => 'nullable|integer|max:999999',
            'accessory.note'        => 'nullable|string|max:255',
            'accessory.provider_id' => 'required|exists:providers,id',
        ];
    }

    public function exportSelected(): StreamedResponse
    {
        $csv = response()->streamDownload(function () {
            /* toCsv function you can get it in AppServiceProvider as macro*/
            echo $this->getselectedRowsQuery()->toCsv();
        }, 'Accessories' . today() . '.csv');
        $this->notify('Accessories have been downloaded successfully!');
        return $csv;
    }

    public function deleteSelected(): void
    {
        $this->getselectedRowsQuery()->delete();
        $this->selectedPage = false;
        $this->selectedAll = false;
        $this->notify('Accessories has been deleted successfully!');
    }

    public function edit($oreId)
    {
        $this->useCachedRows();
        $this->accessory = Accessory::find($oreId);
    }

    public function create()
    {
        $this->useCachedRows();
        $this->accessory = new Accessory();
    }

    public function updateOrCreate()
    {
        $this->validate();
        $this->accessory->save();
        $this->notify('Accessory has been saved successfully!');
    }

    public function toggleAdvancedSearch(): void
    {
        $this->useCachedRows();
        $this->showAdvancedSearch = !$this->showAdvancedSearch;
    }

    private function notify(string $message = '', string $color = '#4fbe87')
    {
        $this->dispatchBrowserEvent('notify', ['message' => $message, 'color' => $color]);
    }

    public function getRowsQueryProperty()
    {
        // with
        $query = Accessory::query()
            ->with('provider');

        //search
        $query->when($this->filters['search'] ?? null, function ($query) {
            $query->search('name', $this->filters['search']);
        });
        //filters

        $query->when($this->filters['provider_id'] ?? null, function ($query) {
            $query->where('provider_id', $this->filters['provider_id']);
        });

        $query->when($this->filters['quantity_min'] ?? null, function ($query) {
            $query->where('quantity', '>', $this->filters['quantity_min']);
        });
        $query->when($this->filters['quantity_max'] ?? null, function ($query) {
            $query->where('quantity', '<=', $this->filters['quantity_max']);
        });

        $query->when($this->filters['amount_min'] ?? null, function ($query) {
            $query->where('amount', '>', $this->filters['amount_min']);
        });

        $query->when($this->filters['amount_max'] ?? null, function ($query) {
            $query->where('amount', '<=', $this->filters['amount_max']);
        });

        $query->when($this->filters['height'] ?? null, function ($query) {
            $heights = explode(',', $this->filters['height']);
            $query->whereIn('height', $heights);
        });

        $query->when($this->filters['weight'] ?? null, function ($query) {
            $weights = explode(',', $this->filters['weight']);
            $query->whereIn('weight', $weights);
        });

        // sorting
        $query = $this->applySorting($query);
        return $query;
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->applyPaginatation($this->rowsQuery);
        });
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }


    public function render()
    {
        if ($this->selectedAll) {
            $this->selectPageRows();
        }

        if (!$this->providers) {
            $this->providers = Provider::select('id', 'name')->get();
        }

        return view('livewire.materials.accessories', [
            'models' => $this->rows,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
