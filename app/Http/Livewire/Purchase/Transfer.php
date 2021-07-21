<?php

namespace App\Http\Livewire\Purchase;

use App\Enums\OrderStatus;
use App\Http\Livewire\Datatable\WithBulkActions;
use App\Http\Livewire\Datatable\WithCachedRows;
use App\Http\Livewire\Datatable\WithPerPagePagination;
use App\Http\Livewire\Datatable\WithSorting;
use App\Models\Purchase as PurchaseModel;
use App\Models\Transfer as TransferModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Transfer extends Component
{
    use WithFileUploads, WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public string $pageTitle = 'Transfers';
    public bool $showAdvancedSearch = false;
    public $purchases;
    public TransferModel $transfer;
    protected $queryString = ['sortField', 'sortDirection', 'filters'];
    protected $listeners = ['refreshTransfer', '$refresh'];

    public array $filters = [
        'search'      => null,
        'purchase_id' => null,
        'amount_min'  => null,
        'amount_max'  => null,
        'date_start'  => null,
        'date_end'    => null,
    ];

    public function mount()
    {
        $this->transfer = new TransferModel();
    }

    public function rules(): array
    {
        $remain = 0;
        if (isset($this->transfer)) {
            $remain = PurchaseModel::find($this->transfer->purchase_id)->remain ?? 0;
        }
        return [
            'transfer.date'        => 'required',
            'transfer.amount'      => 'required|numeric|max:' . $remain,
            'transfer.note'        => 'nullable|string|max:255',
            'transfer.purchase_id' => 'required|exists:purchases,id',
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
        }, 'Transfers' . today() . '.csv');
        $this->notify('Transfers have been downloaded successfully!');
        return $csv;
    }

    public function deleteSelected(): void
    {
        DB::beginTransaction();
        $transfers = $this->getselectedRowsQuery()->get();
        foreach ($transfers as $transfer) {
            $purchase = $transfer->purchase;
            $purchase->remain += $transfer->amount;
            $purchase->paid_amount -= $transfer->amount;
            $purchase->getStatus();
            $purchase->save();
            $transfer->delete();
        }
        DB::commit();
        $this->selectedPage = false;
        $this->selectedAll = false;
        $this->resetPage();
        $this->notify('Transfers has been deleted successfully!');
    }

    public function edit($transferId)
    {
        $this->useCachedRows();
        $this->transfer = TransferModel::find($transferId);
    }

    public function create()
    {
        $this->useCachedRows();
        $this->transfer = new TransferModel();
    }

    public function updateOrCreate()
    {
        $this->validate();
        $this->transfer->save();
        $this->notify('Transfer has been saved successfully!');
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
        $query = TransferModel::query()
            ->with('purchase.provider');

        $query->when($this->filters['search'] ?? null, function ($query) {
            $query->whereHas('purchase.provider', function ($query) {
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
        $query->when($this->filters['date_start'] ?? null, function ($query) {
            $query->whereDate('date', '>', $this->filters['date_start']);
        });
        $query->when($this->filters['date_end'] ?? null, function ($query) {
            $query->where('date', '<=', $this->filters['date_end']);
        });

        $query->when($this->filters['purchase_id'] ?? null, function ($query) {
            $query->where('purchase_id', $this->filters['purchase_id']);
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

        if (!$this->purchases) {
            $this->purchases = PurchaseModel::query()
                ->where('status', '!=', OrderStatus::DONE)
                ->select('id')->get();
        }

        return view('livewire.purchase.transfer', [
            'models' => $this->rows,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
