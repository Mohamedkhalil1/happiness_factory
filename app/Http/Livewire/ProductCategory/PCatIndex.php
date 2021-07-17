<?php

namespace App\Http\Livewire\ProductCategory;

use App\Http\Livewire\Datatable\WithBulkActions;
use App\Http\Livewire\Datatable\WithCachedRows;
use App\Http\Livewire\Datatable\WithPerPagePagination;
use App\Http\Livewire\Datatable\WithSorting;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PCatIndex extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public string $pageTitle = 'Categories';
    public ProductCategory $category;
    protected $queryString = ['sortField', 'sortDirection', 'filters'];
    protected $listeners = ['refreshCategories', '$refresh'];
    public array $filters = [
        'name' => '',
    ];

    public function rules(): array
    {
        return [
            'category.name'        => 'required|string|max:255',
            'category.description' => 'nullable|string|max:640000',
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
        }, 'Categories' . today() . '.csv');
        $this->notify('Categories have been downloaded successfully!');
        return $csv;
    }

    public function deleteSelected(): void
    {
        $this->getselectedRowsQuery()->delete();
        $this->selectedPage = false;
        $this->selectedAll = false;
        $this->resetPage();
        $this->category = new ProductCategory();
        $this->notify('Categories has been deleted successfully!');
    }

    public function edit($categoryId)
    {
        $this->useCachedRows();
        $this->category = ProductCategory::find($categoryId);
        if (!$this->category) {
            $this->notify('Category is not found!', "#ff8888");
        }
    }

    public function create()
    {
        $this->useCachedRows();
        $this->category = new ProductCategory();
    }

    public function updateOrCreate()
    {
        $this->validate();
        $this->category->save();
        $this->notify('Category has been saved successfully!');
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
        $query = ProductCategory::query()
            ->search('name', $this->filters['search'] ?? null);
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

        return view('livewire.product-category.p-cat-index', [
            'models' => $this->rows,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
