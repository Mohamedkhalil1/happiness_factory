<?php

namespace App\Http\Livewire\Inventory;

use App\Http\Livewire\Datatable\WithBulkActions;
use App\Http\Livewire\Datatable\WithCachedRows;
use App\Http\Livewire\Datatable\WithPerPagePagination;
use App\Http\Livewire\Datatable\WithSorting;
use App\Http\Livewire\InventoriesFilters\WithInventoriesFilters;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Season;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvIndex extends Component
{
    use WithFileUploads, WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows, WithInventoriesFilters;

    public string $pageTitle = 'Inventories';
    public bool $showAdvancedSearch = false;
    public $seasons;
    public $categories;
    public $products;
    public Inventory $inventory;
    public $avatar;

    protected $queryString = ['sortField', 'sortDirection', 'filters'];
    protected $listeners = ['refreshInventories', '$refresh'];

    public function rules(): array
    {
        return [
            'inventory.price'      => 'required|numeric|max:9999999',
            'inventory.quantity'   => 'required|integer|max:9999999',
            'inventory.color'      => 'required|string|max:255',
            'inventory.size'       => 'required|string|max:255',
            'inventory.image'      => 'required|string|max:255',
            'inventory.product_id' => 'required|exists:products,id',
        ];
    }

    public function exportSelected(): StreamedResponse
    {
        $csv = response()->streamDownload(function () {
            /* toCsv function you can get it in AppServiceProvider as macro*/
            echo $this->getselectedRowsQuery()->toCsv();
        }, 'Products' . today() . '.csv');
        $this->notify('Products have been downloaded successfully!');
        return $csv;
    }

    public function deleteSelected(): void
    {
        $this->getselectedRowsQuery()->whereDoesntHave('orders')->delete();
        $this->selectedPage = false;
        $this->selectedAll = false;
        $this->notify('Products has been deleted successfully!');
    }

    public function edit($inventoryId)
    {
        $this->useCachedRows();
        $this->inventory = Inventory::find($inventoryId);
    }

    public function create()
    {
        $this->useCachedRows();
        $this->inventory = new Inventory();
    }

    public function updateOrCreate()
    {
        $this->validate();
        $this->avatar && $this->inventory->image = $this->avatar->store('/', 'files');
        $this->inventory->save();
        $this->notify('Inventory has been saved successfully!');
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
        if (!$this->seasons) {
            $this->seasons = Season::select('id', 'name')->get();
        }
        if (!$this->categories) {
            $this->categories = ProductCategory::select('id', 'name')->get();
        }
        if (!$this->products) {
            $this->products = Product::select('id', 'name')->get();
        }

        return view('livewire.inventory.inv-index', [
            'models'     => $this->rows,
            'seasons'    => $this->seasons,
            'categories' => $this->categories,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
