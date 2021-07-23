<?php

namespace App\Http\Livewire\Purchase;

use App\Enums\OrderStatus;
use App\Http\Livewire\Datatable\WithBulkActions;
use App\Http\Livewire\Datatable\WithCachedRows;
use App\Http\Livewire\Datatable\WithPerPagePagination;
use App\Http\Livewire\Datatable\WithSorting;
use App\Models\Material;
use App\Models\Provider;
use App\Models\Purchase as PurchaseModel;
use App\Models\Transfer;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Purchase extends Component
{
    use WithFileUploads, WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public string $pageTitle = 'Purchases';
    public bool $showAdvancedSearch = false;
    protected $materials;
    protected $providers;
    public PurchaseModel $purchase;
    public Transfer $transfer;
    public int $purchaseId;
    public bool $edit = false;
    protected $queryString = ['sortField', 'sortDirection', 'filters'];
    protected $listeners = ['refreshPurchases', '$refresh'];

    public array $filters = [
        'search'       => null,
        'amount_min'   => null,
        'amount_max'   => null,
        'date_start'   => null,
        'date_end'     => null,
        'status'       => null,
    ];

    public function rules(): array
    {
        return [
            'purchase.material_id'  => 'required|exists:materials,id',
            'purchase.provider_id'  => 'required|exists:providers,id',
            'purchase.date'         => 'required|date',
            'purchase.color'        => 'nullable|string|max:255',
            'purchase.height'       => 'nullable|numeric|max:9999999',
            'purchase.width'        => 'nullable|numeric|max:9999999',
            'purchase.weight'       => 'nullable|numeric|max:9999999',
            'purchase.amount'       => 'required|numeric|min:1|max:999999',
            'purchase.total_amount' => 'required|numeric|min:1|max:999999',
            'transfer.date'         => 'nullable',
            'transfer.amount'       => 'nullable',
            'transfer.note'         => 'nullable',
            'transfer.purchase_id'  => 'nullable',
        ];
    }

    public function mount()
    {
        $this->transfer = new Transfer();
    }


    public function updatedPurchaseAmount($value)
    {
        if ($this->purchase->height) {
            $this->purchase->total_amount = $this->purchase->height * $value;
        } elseif ($this->purchase->weight) {
            $this->purchase->total_amount = $this->purchase->weight * $value;
        }
    }

    public function updatedPurchaseHeight($value)
    {
        if ($this->purchase->amount) {
            $this->purchase->total_amount = $this->purchase->amount * $value;
        }
    }

    public function updatedPurchaseWeight($value)
    {
        if ($this->purchase->amount) {
            $this->purchase->total_amount = $this->purchase->amount * $value;
        }
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
        }, 'Products' . today() . '.csv');
        $this->notify('Purchases have been downloaded successfully!');
        return $csv;
    }

    public function delete($purchaseId): void
    {
        $purchase = $this->rows->find($purchaseId);
        if (!$purchase) {
            $this->notify('Purchase is not found!');
        } else {
            if ($purchase->status != OrderStatus::PENDING) {
                $this->notify('You can not delete this order.', '#dc3545');
                return;
            }
            $purchase->delete();
            $this->resetFilters();
            $this->notify('Purchase has been deleted successfully!');

        }
    }

    public function create()
    {
        $this->useCachedRows();
        $this->edit = false;
        $this->purchase = new PurchaseModel();
    }

    public function edit($purchaseId)
    {
        $this->useCachedRows();
        $this->edit = true;
        $this->purchase = PurchaseModel::find($purchaseId);
    }

    public function updateOrCreate()
    {
        $this->validate();
        $this->purchase->remain = $this->purchase->total_amount;
        $this->purchase->save();
        $this->notify('Purchase has been saved successfully!');
    }

    public function storeModelId($id)
    {
        $this->purchaseId = $id;
    }

    public function makeTransfer()
    {
        $this->transfer->purchase_id = $this->purchaseId;
        $purchase = PurchaseModel::find($this->purchaseId);
        if (!$purchase) {
            $this->notify('purchase is not found');
            return;
        }
        $this->validate([
            'transfer.date'        => 'required|date',
            'transfer.amount'      => 'required|numeric|min:1|max:' . $purchase->remain,
            'transfer.note'        => 'nullable|string|max:255',
            'transfer.purchase_id' => 'required|exists:purchases,id',
        ]);
        $this->transfer->save();
        $this->transfer = new Transfer();
        $this->notify('Transfer has been done successfully');
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
        // with
        $query = PurchaseModel::query()
            ->with(['material', 'provider']);

        //searching
        $query->when($this->filters['search'] ?? null, function ($query) {
            $query->whereHas('provider', function ($query) {
                $query->search('name', $this->filters['search']);
            })
                ->orWhereHas('material', function ($query) {
                    $query->search('name', $this->filters['search']);
                });
        });
        //filters

        $query->when($this->filters['amount_min'] ?? null, function ($query) {
            $query->where('total_amount', '>', $this->filters['amount_min']);
        });
        $query->when($this->filters['amount_max'] ?? null, function ($query) {
            $query->where('total_amount', '<=', $this->filters['amount_max']);
        });


        $query->when($this->filters['quantity_min'] ?? null, function ($query) {
            $query->where('quantity', '>', $this->filters['quantity_min']);
        });
        $query->when($this->filters['quantity_max'] ?? null, function ($query) {
            $query->where('quantity', '<=', $this->filters['quantity_max']);
        });

        $query = $query->when($this->filters['date_start'] ?? null, function ($query) {
            $query->whereDate('date', '>', $this->filters['date_start']);
        });
        $query = $query->when($this->filters['date_end'] ?? null, function ($query) {
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

        if (!$this->materials) {
            $this->materials = Material::select('id', 'name')->get();
        }
        if (!$this->providers) {
            $this->providers = Provider::select('id', 'name')->get();
        }

        return view('livewire.purchase.purchase', [
            'models'    => $this->rows,
            'materials' => $this->materials,
            'providers' => $this->providers,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
