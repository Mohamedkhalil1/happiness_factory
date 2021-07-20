<?php

namespace App\Http\Livewire\Provider;

use App\Http\Livewire\Datatable\WithBulkActions;
use App\Http\Livewire\Datatable\WithCachedRows;
use App\Http\Livewire\Datatable\WithPerPagePagination;
use App\Http\Livewire\Datatable\WithSorting;
use App\Models\Client;
use App\Models\Provider;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PrvIndex extends Component
{
    use WithFileUploads, WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public string $pageTitle = 'Providers';
    public bool $showAdvancedSearch = false;
    public Provider $provider;
    protected $queryString = ['sortField', 'sortDirection', 'filters'];
    protected $listeners = ['refreshProviders', '$refresh'];

    public array $filters = [
        'search'     => null,
        'type'       => null,
        'date_start' => null,
        'amount_min' => null,
        'date_end'   => null,
        'amount_max' => null,
    ];

    public function rules(): array
    {
        return [
            'provider.name'        => 'required',
            'provider.type'        => 'nullable|between:1,2',
            'provider.address'     => 'nullable|string|max:255',
            'provider.phone'       => 'nullable|numeric|digits_between:11,13',
            'provider.worked_date' => 'nullable|date',
            'provider.details'     => 'nullable|string|max:64000',
        ];
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function exportSelected(): StreamedResponse
    {
        $csv = response()->streamDownload(function () {
            /* toCsv function you can get it in AppServiceProvider as macro*/
            echo $this->getselectedRowsQuery()->toCsv();
        }, 'Providers' . today() . '.csv');
        $this->notify('Providers have been downloaded successfully!');
        return $csv;
    }

    public function deleteSelected(): void
    {
        $this->getselectedRowsQuery()->delete();
        $this->selectedPage = false;
        $this->selectedAll = false;
        $this->resetPage();
        $this->notify('Providers has been deleted successfully!');
    }

    public function edit($providerId)
    {
        $this->useCachedRows();
        $this->provider = Provider::find($providerId);
    }

    public function create()
    {
        $this->useCachedRows();
        $this->provider = new Provider();
    }

    public function updateOrCreate()
    {
        $this->validate();
        $this->provider->save();
        $this->notify('Provider has been saved successfully!');
    }

    public function toggleAdvancedSearch(): void
    {
        $this->useCachedRows();
        $this->showAdvancedSearch = !$this->showAdvancedSearch;
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    private function notify(string $message = '', string $color = '#4fbe87')
    {
        $this->dispatchBrowserEvent('notify', ['message' => $message, 'color' => $color]);
    }

    #use cashing in the same request
    public function getRowsQueryProperty()
    {
        // searching
        $query = Provider::query()
            ->search('name', $this->filters['search'] ?? null);
        //filters
        $query = $query->when($this->filters['type'] ?? null, function ($query) {
            $query->where('type', $this->filters['type']);
        });

        $query = $query->when($this->filters['amount_min'] ?? null, function ($query) {
            $query->where('salary', '>', $this->filters['amount_min']);
        });
        $query = $query->when($this->filters['amount_max'] ?? null, function ($query) {
            $query->where('salary', '<=', $this->filters['amount_max']);
        });
        $query = $query->when($this->filters['date_start'] ?? null, function ($query) {
            $query->whereDate('worked_date', '>', $this->filters['date_start']);
        });
        $query = $query->when($this->filters['date_end'] ?? null, function ($query) {
            $query->where('worked_date', '<=', $this->filters['date_end']);
        });

        $query = $query->when($this->filters['category_id'] ?? null, function ($query) {
            $query->where('category_id', $this->filters['category_id']);
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

    public function render()
    {
        if ($this->selectedAll) {
            $this->selectPageRows();
        }

        return view('livewire.provider.prv-index', [
            'models' => $this->rows,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
