<?php

namespace App\Http\Livewire\Client;

use App\Http\Livewire\Datatable\WithBulkActions;
use App\Http\Livewire\Datatable\WithCachedRows;
use App\Http\Livewire\Datatable\WithPerPagePagination;
use App\Http\Livewire\Datatable\WithSorting;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CliIndex extends Component
{
    use WithFileUploads, WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public string $pageTitle = 'Clients';
    public bool $showAdvancedSearch = false;
    public Client $client;
    protected $queryString = ['sortField', 'sortDirection', 'filters'];
    protected $listeners = ['refreshClients', '$refresh'];

    public $avatar;

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
            'client.name'        => 'required',
            'client.type'        => 'nullable|between:1,2',
            'client.address'     => 'nullable|string|max:255',
            'client.phone'       => 'nullable|numeric|digits_between:11,13',
            'client.worked_date' => 'nullable|date',
            'client.details'     => 'nullable|string|max:64000',
            'client.avatar'      => 'nullable|string',
            'avatar'             => 'nullable|image|max:1048',
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
        }, 'Client' . today() . '.csv');
        $this->notify('Clients have been downloaded successfully!');
        return $csv;
    }

    public function deleteSelected(): void
    {
        $this->getselectedRowsQuery()->whereDoesntHave('orders')->delete();
        $this->selectedPage = false;
        $this->selectedAll = false;
        $this->resetPage();
        $this->notify('Clients has been deleted successfully!');
    }

    public function edit($clientId)
    {
        $this->useCachedRows();
        $this->client = Client::find($clientId);
    }

    public function create()
    {
        $this->useCachedRows();
        $this->client = new Client();
    }

    public function updateOrCreate()
    {
        $this->validate();
        $this->avatar && $this->client->avatar = $this->avatar->store('/', 'files');

        $this->client->save();
        $this->notify('Client has been saved successfully!');
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
        $query = Client::query()
            ->with('orders')
            ->search('name', $this->filters['search'] ?? null);
        //filters

        $query->when($this->filters['amount_min'] ?? null, function ($query) {
            $query->select(
                'clients.id', 'clients.name', 'clients.type', 'clients.address', 'clients.phone', 'clients.worked_date','avatar'
                , DB::raw('SUM(orders.amount_after_discount) as amount_sum'))
                ->join('orders', 'orders.client_id', '=', 'clients.id')
                ->groupBy('clients.id', 'clients.name', 'clients.type', 'clients.address', 'clients.phone', 'clients.worked_date','clients.avatar')
                ->havingRaw('amount_sum > ?', [$this->filters['amount_min']]);
        });

        $query->when($this->filters['amount_max'] ?? null, function ($query) {
            $query->select(
                'clients.id', 'clients.name', 'clients.type', 'clients.address', 'clients.phone', 'clients.worked_date','avatar'
                , DB::raw('SUM(orders.amount_after_discount) as amount_sum'))
                ->join('orders', 'orders.client_id', '=', 'clients.id')
                ->groupBy('clients.id', 'clients.name', 'clients.type', 'clients.address', 'clients.phone', 'clients.worked_date','clients.avatar')
                ->havingRaw('amount_sum <= ?', [$this->filters['amount_max']]);
        });


        $query->when($this->filters['date_start'] ?? null, function ($query) {
            $query->whereDate('worked_date', '>', $this->filters['date_start']);
        });
        $query->when($this->filters['date_end'] ?? null, function ($query) {
            $query->where('worked_date', '<=', $this->filters['date_end']);
        });
        $query->when($this->filters['type'] ?? null, function ($query) {
            $query->where('type', $this->filters['type']);
        });

        $query->when($this->filters['category_id'] ?? null, function ($query) {
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

        return view('livewire.client.cli-index', [
            'models' => $this->rows,
        ])
            ->extends('layouts.app')
            ->section('content');
    }

}
