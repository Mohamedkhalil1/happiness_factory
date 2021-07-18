<?php

namespace App\Http\Livewire\Product;

use App\Http\Livewire\Datatable\WithBulkActions;
use App\Http\Livewire\Datatable\WithCachedRows;
use App\Http\Livewire\Datatable\WithPerPagePagination;
use App\Http\Livewire\Datatable\WithSorting;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Season;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProIndex extends Component
{
    use WithFileUploads, WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public string $pageTitle = 'Products';
    public bool $showAdvancedSearch = false;
    public $seasons;
    public $categories;
    public Product $product;
    protected $queryString = ['sortField', 'sortDirection', 'filters'];
    protected $listeners = ['resfreshEmployees', '$refresh'];

    public array $filters = [
        'search'      => null,
        'season_id'   => null,
        'category_id' => null,
    ];

    public function rules(): array
    {
        return [
            'product.name'        => 'required',
            'product.description' => 'nullable|string|max:640000',
            'product.season_id'   => 'required|exists:seasons,id',
            'product.category_id' => 'required|exists:product_categories,id',
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
        }, 'Products' . today() . '.csv');
        $this->notify('Products have been downloaded successfully!');
        return $csv;
    }

    public function deleteSelected(): void
    {
        $this->getselectedRowsQuery()->delete();
        $this->selectedPage = false;
        $this->selectedAll = false;
        $this->notify('Products has been deleted successfully!');
    }

    public function edit($productId)
    {
        $this->useCachedRows();
        $this->product = Product::find($productId);
    }

    public function updateOrCreate()
    {
        $this->validate();
        $this->product->save();
        $this->notify('Product has been saved successfully!');
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
        $query = Product::query()
            ->with(['category', 'inventories', 'season']);

        //filters
        $query->search('name', $this->filters['search'] ?? null);
        $query = $query->when($this->filters['category_id'] ?? null, function ($query) {
            $query->where('category_id', $this->filters['category_id']);
        });
        $query = $query->when($this->filters['season_id'] ?? null, function ($query) {
            $query->where('season_id', $this->filters['season_id']);
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
        if (!$this->seasons) {
            $this->seasons = Season::select('id', 'name')->get();
        }
        if (!$this->categories) {
            $this->categories = ProductCategory::select('id', 'name')->get();
        }

        return view('livewire.product.pro-index', [
            'models'     => $this->rows,
            'seasons'    => $this->seasons,
            'categories' => $this->categories,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
