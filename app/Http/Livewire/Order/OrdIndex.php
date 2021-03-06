<?php

namespace App\Http\Livewire\Order;

use App\Enums\OrderStatus;
use App\Http\Livewire\Datatable\WithBulkActions;
use App\Http\Livewire\Datatable\WithCachedRows;
use App\Http\Livewire\Datatable\WithPerPagePagination;
use App\Http\Livewire\Datatable\WithSorting;
use App\Models\Order;
use App\Models\Transcation;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrdIndex extends Component
{
    use WithFileUploads, WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public string $pageTitle = 'Orders';
    public bool $showAdvancedSearch = false;
    public Transcation $transaction;
    public int $orderId;
    protected $queryString = ['sortField', 'sortDirection', 'filters'];
    protected $listeners = ['refreshOrders', '$refresh'];

    public array $filters = [
        'search'     => null,
        'amount_min' => null,
        'amount_max' => null,
        'date_start' => null,
        'date_end'   => null,
        'status'     => null,
    ];

    public function rules(): array
    {
        $maxAmount = Order::find($this->orderId ?? 0)->remain ?? 0;
        return [
            'transaction.date'     => 'required',
            'transaction.amount'   => 'required|numeric|max:' . $maxAmount,
            'transaction.note'     => 'nullable|string|max:255',
            'transaction.order_id' => 'required|exists:orders,id',
        ];
    }

    public function mount()
    {
        $this->transaction = new Transcation();
    }


    public function storeModelId($id)
    {
        $this->orderId = $id;
    }

    public function storeTransaction()
    {
        $this->transaction->order_id = $this->orderId;
        $this->validate();
        $this->transaction->save();
        $this->transaction = new Transcation();
        $this->notify('Transaction has been done successfully');
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
        }, 'Orders' . today() . '.csv');
        $this->notify('Orders have been downloaded successfully!');
        return $csv;
    }

    public function delete($orderId): void
    {
        $order = $this->rows->find($orderId);
        if (!$order) {
            $this->notify('Orders is not found!');
        } else {
            if ($order->status != OrderStatus::PENDING) {
                $this->notify('You can not delete this order.','#dc3545');
                return;
            }
            $order->delete();
            $this->resetFilters();
            $this->notify('Order has been deleted successfully!');

        }
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
        $query = Order::query()
            ->with('client');

        //search
        $query->when($this->filters['search'] ?? null, function ($query) {
            $query->whereHas('client', function ($query) {
                $query->where('name', 'LIKE', '%' . $this->filters['search'] . '%');
            });
        });
        //filters
        $query->when($this->filters['amount_min'] ?? null, function ($query) {
            $query->where('amount_after_discount', '>', $this->filters['amount_min']);
        });
        $query->when($this->filters['amount_max'] ?? null, function ($query) {
            $query->where('amount_after_discount', '<=', $this->filters['amount_max']);
        });
        $query->when($this->filters['date_start'] ?? null, function ($query) {
            $query->whereDate('date', '>', $this->filters['date_start']);
        });
        $query->when($this->filters['date_end'] ?? null, function ($query) {
            $query->where('date', '<=', $this->filters['date_end']);
        });
        $query->when($this->filters['status'] ?? null, function ($query) {
            $query->where('status', $this->filters['status']);
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

        return view('livewire.order.ord-index', [
            'models' => $this->rows,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
