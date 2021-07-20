<?php

namespace App\Http\Livewire\Transaction;

use App\Http\Livewire\Datatable\WithBulkActions;
use App\Http\Livewire\Datatable\WithCachedRows;
use App\Http\Livewire\Datatable\WithPerPagePagination;
use App\Http\Livewire\Datatable\WithSorting;
use App\Models\Order;
use App\Models\Transcation;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TraIndex extends Component
{
    use WithFileUploads, WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public string $pageTitle = 'Transactions';
    public bool $showAdvancedSearch = false;
    public $orders;
    public Transcation $transaction;
    protected $queryString = ['sortField', 'sortDirection', 'filters'];
    protected $listeners = ['refreshTransactions', '$refresh'];

    public array $filters = [
        'search'     => null,
        'order_id'   => null,
        'amount_min' => null,
        'amount_max' => null,
        'date_start' => null,
        'date_end'   => null,
    ];

    public function rules(): array
    {
        return [
            'transaction.date'     => 'required',
            'transaction.amount'   => 'required|numeric|max:9999999',
            'transaction.note'     => 'nullable|string|max:255',
            'transaction.order_id' => 'required|exists:orders,id',
        ];
    }

    public function mount()
    {
        $this->transaction = new Transcation();
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
        }, 'Transactions' . today() . '.csv');
        $this->notify('Transactions have been downloaded successfully!');
        return $csv;
    }

    public function deleteSelected(): void
    {
        DB::beginTransaction();
        $transactions = $this->getselectedRowsQuery()->get();
        foreach ($transactions as $transaction) {
            $order = $transaction->order;
            $order->remain += $transaction->amount;
            $order->save();
        }
        DB::commit();
        $this->selectedPage = false;
        $this->selectedAll = false;
        $this->resetPage();
        $this->notify('Transaction has been deleted successfully!');
    }

    public function edit($transactionId)
    {
        $this->useCachedRows();
        $this->transaction = Transcation::find($transactionId);
    }

    public function create()
    {
        $this->useCachedRows();
        $this->transaction = new Transcation();
    }

    public function updateOrCreate()
    {
        DB::beginTransaction();
        $this->validate();
        $this->transaction->save();
        DB::commit();
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

    private function notify(string $message = '', string $color = '#4fbe87')
    {
        $this->dispatchBrowserEvent('notify', ['message' => $message, 'color' => $color]);
    }

    #use cashing in the same request
    public function getRowsQueryProperty()
    {
        // searching
        $query = Transcation::query()
            ->with('order.client');

        $query->when($this->filters['search'] ?? null, function ($query) {
            $query->whereHas('order.client', function ($query) {
                $query->search('name', $this->filters['search']);
            });
        });
        //filters

        $query->when($this->filters['amount_min'] ?? null, function ($query) {
            $query->where('amount', '>', $this->filters['amount_min']);
        });
        $query->when($this->filters['amount_max'] ?? null, function ($query) {
            $query->where('amount', '<=', $this->filters['amount_max']);
        });
        $query->when($this->filters['date'] ?? null, function ($query) {
            $query->whereDate('date', '>', $this->filters['date_start']);
        });
        $query->when($this->filters['date_end'] ?? null, function ($query) {
            $query->where('date', '<=', $this->filters['date_end']);
        });

        $query->when($this->filters['order_id'] ?? null, function ($query) {
            $query->where('order_id', $this->filters['order_id']);
        });
        // sorting
        return $this->applySorting($query);
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

        if (!$this->orders) {
            $this->orders = Order::select('id')->get();
        }

        return view('livewire.transaction.tra-index', [
            'models' => $this->rows,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
