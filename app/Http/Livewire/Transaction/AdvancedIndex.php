<?php

namespace App\Http\Livewire\Transaction;

use App\Http\Livewire\Datatable\WithBulkActions;
use App\Http\Livewire\Datatable\WithCachedRows;
use App\Http\Livewire\Datatable\WithPerPagePagination;
use App\Http\Livewire\Datatable\WithSorting;
use App\Models\Transaction;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdvancedIndex extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public string $pageTitle = 'Advanced Transactions';
    public bool $showAdvancedSearch = false;
    public Transaction $transaction;
    protected $queryString = ['sortField', 'sortDirection', 'filters' ,'id'];
    protected $listeners = ['resfreshTransactions', '$refresh'];
    public array $filters = [
        'search'     => '',
        'status'     => '',
        'amount_min' => null,
        'amount_max' => null,
        'date_start' => null,
        'date_end'   => null,
    ];

    public function rules(): array
    {
        return [
            'transaction.title'  => 'required',
            'transaction.amount' => 'required|numeric|max:640000',
            'transaction.date'   => 'required|date',
            'transaction.status' => 'required|between:1,3',
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
        }, 'transactions.csv');
        $this->notify('Transactions have been downloaded successfully!');
        return $csv;
    }

    public function deleteSelected(): void
    {
        $this->getselectedRowsQuery()->delete();
        $this->selectedPage = false;
        $this->selectedAll = false;
        $this->notify('Transactions has been deleted successfully!');
    }

    public function edit($transactionId)
    {
        $this->useCachedRows();
        $this->transaction = Transaction::find($transactionId);
    }

    public function create()
    {
        $this->useCachedRows();
        $this->transaction = new Transaction();
    }

    public function updateOrCreate()
    {
        $this->validate();
        $this->transaction->save();
        $this->notify('Transaction has been saved successfully!');
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

    private function notify(string $message = '', string $color = '4fbe87')
    {
        $this->dispatchBrowserEvent('notify', ['message' => $message, 'color' => $color]);
    }

    #use cashing in the same request
    public function getRowsQueryProperty()
    {
        // searching
        $query = Transaction::query()
            ->search('title', $this->filters['search'] ?? null);

        // sorting
        $query = $this->applySorting($query);

        //filters
        $query = $query->when($this->filters['status'] ?? null, function ($query) {
            $query->where('status', $this->filters['status']);
        });
        $query = $query->when($this->filters['amount_min'] ?? null, function ($query) {
            $query->where('amount', '>', $this->filters['amount_min']);
        });
        $query = $query->when($this->filters['amount_max'] ?? null, function ($query) {
            $query->where('amount', '<=', $this->filters['amount_max']);
        });
        $query = $query->when($this->filters['date_start'] ?? null, function ($query) {
            $query->whereDate('date', '>', $this->filters['date_start']);
        });
        $query = $query->when($this->filters['date_end'] ?? null, function ($query) {
            $query->where('date', '<=', $this->filters['date_end']);
        });
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

        return view('livewire.transaction.advanced-index', [
            'transactions' => $this->rows,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
