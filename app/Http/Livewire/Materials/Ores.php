<?php

namespace App\Http\Livewire\Materials;

use App\Http\Livewire\Datatable\WithBulkActions;
use App\Http\Livewire\Datatable\WithCachedRows;
use App\Http\Livewire\Datatable\WithPerPagePagination;
use App\Http\Livewire\Datatable\WithSorting;
use App\Models\Material;
use App\Models\Ore;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Ores extends Component
{
    use WithFileUploads, WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public string $pageTitle = 'Ores';
    public bool $showAdvancedSearch = false;
    public $materials;
    public Ore $ore;

    public $image;

    protected $queryString = ['sortField', 'sortDirection', 'filters'];
    protected $listeners = ['refreshOres', '$refresh'];

    public array $filters = [
        'search'       => null,
        'quantity_min' => null,
        'quantity_max' => null,
        'color'        => null,
        'height'       => null,
        'width'        => null,
        'weight'       => null,
    ];


    public function rules(): array
    {
        return [
            'ore.color'       => 'required|string|max:9999999',
            'ore.height'      => 'required|string|max:9999999',
            'ore.width'       => 'required|string|max:255',
            'ore.weight'      => 'required|string|max:255',
            'ore.quantity'    => 'required|integer|max:999999',
            'image'           => 'nullable|image|max:1024',
            'ore.material_id' => 'required|exists:materials,id',
        ];
    }

    public function exportSelected(): StreamedResponse
    {
        $csv = response()->streamDownload(function () {
            /* toCsv function you can get it in AppServiceProvider as macro*/
            echo $this->getselectedRowsQuery()->toCsv();
        }, 'Ores' . today() . '.csv');
        $this->notify('Ores have been downloaded successfully!');
        return $csv;
    }

    public function deleteSelected(): void
    {
        $this->getselectedRowsQuery()->delete();
        $this->selectedPage = false;
        $this->selectedAll = false;
        $this->notify('Ores has been deleted successfully!');
    }

    public function edit($oreId)
    {
        $this->useCachedRows();
        $this->ore = Ore::find($oreId);
    }

    public function create()
    {
        $this->useCachedRows();
        $this->ore = new Ore();
    }

    public function updateOrCreate()
    {
        $this->validate();

        $this->image && $this->ore->image = $this->image->store('/', 'files');
        $this->ore->save();
        $this->notify('Ore has been saved successfully!');
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

    public function getRowsQueryProperty()
    {
        // with
        $query = Ore::query()
            ->with('material');

        //search
        $query->when($this->filters['search'] ?? null, function ($query) {
            $query->whereHas('material', function ($query) {
                $query->search('name', $this->filters['search']);
            });
        });
        //filters

        $query->when($this->filters['quantity_min'] ?? null, function ($query) {
            $query->where('quantity', '>', $this->filters['quantity_min']);
        });
        $query->when($this->filters['quantity_max'] ?? null, function ($query) {
            $query->where('quantity', '<=', $this->filters['quantity_max']);
        });

        $query->when($this->filters['color'] ?? null, function ($query) {
            $colors = explode(',', $this->filters['color']);
            $query->whereIn('color', $colors);
        });

        $query->when($this->filters['height'] ?? null, function ($query) {
            $sizes = explode(',', $this->filters['height']);
            $query->whereIn('height', $sizes);
        });

        $query->when($this->filters['width'] ?? null, function ($query) {
            $sizes = explode(',', $this->filters['width']);
            $query->whereIn('width', $sizes);
        });

        $query->when($this->filters['weight'] ?? null, function ($query) {
            $sizes = explode(',', $this->filters['weight']);
            $query->whereIn('weight', $sizes);
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

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }


    public function render()
    {
        if ($this->selectedAll) {
            $this->selectPageRows();
        }

        if (!$this->materials) {
            $this->materials = Material::select('id', 'name')->get();
        }

        return view('livewire.materials.ores', [
            'models' => $this->rows,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
