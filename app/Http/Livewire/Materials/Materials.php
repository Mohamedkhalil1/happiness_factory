<?php

namespace App\Http\Livewire\Materials;

use App\Http\Livewire\Datatable\WithBulkActions;
use App\Http\Livewire\Datatable\WithCachedRows;
use App\Http\Livewire\Datatable\WithPerPagePagination;
use App\Http\Livewire\Datatable\WithSorting;
use App\Models\Material;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Materials extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public string $pageTitle = 'Materials';
    public Material $material;
    protected $queryString = ['sortField', 'sortDirection', 'filters'];
    protected $listeners = ['refreshMaterials', '$refresh'];
    public array $filters = [
        'name' => '',
    ];

    public function rules(): array
    {
        return [
            'material.name'        => 'required|string|max:255',
            'material.description' => 'nullable|string|max:640000',
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
        }, 'Materials' . today() . '.csv');
        $this->notify('Materials have been downloaded successfully!');
        return $csv;
    }

    public function deleteSelected(): void
    {
        $this->getselectedRowsQuery()->delete();
        $this->selectedPage = false;
        $this->selectedAll = false;
        $this->resetPage();
        $this->notify('Materials has been deleted successfully!');
    }

    public function edit($materialId)
    {
        $this->useCachedRows();
        $this->material = Material::find($materialId);
        if (!$this->material) {
            $this->notify('Materials is not found!', "#ff8888");
        }
    }

    public function create()
    {
        $this->useCachedRows();
        $this->material = new Material();
    }

    public function updateOrCreate()
    {
        $this->validate();
        $this->material->save();
        $this->notify('Material has been saved successfully!');
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
        $query = Material::query()
            ->with('ores')
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

        return view('livewire.materials.materials', [
            'models' => $this->rows,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
