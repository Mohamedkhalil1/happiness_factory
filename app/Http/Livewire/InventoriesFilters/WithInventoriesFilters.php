<?php

namespace App\Http\Livewire\InventoriesFilters;

use App\Models\Inventory;

trait WithInventoriesFilters
{
    public array $filters = [
        'search'       => null,
        'season_id'    => null,
        'category_id'  => null,
        'quantity_min' => null,
        'quantity_max' => null,
        'amount_min'   => null,
        'amount_max'   => null,
        'color'        => null,
        'size'         => null,
    ];

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

#use cashing in the same request
    public function getRowsQueryProperty()
    {
        // with
        $query = Inventory::query()
            ->with(['product', 'product.category', 'product.season']);

        //filters

        $query = $query->when($this->filters['amount_min'] ?? null, function ($query) {
            $query->where('price', '>', $this->filters['amount_min']);
        });
        $query = $query->when($this->filters['amount_max'] ?? null, function ($query) {
            $query->where('price', '<=', $this->filters['amount_max']);
        });

        $query = $query->when($this->filters['quantity_min'] ?? null, function ($query) {
            $query->where('quantity', '>', $this->filters['quantity_min']);
        });
        $query = $query->when($this->filters['quantity_max'] ?? null, function ($query) {
            $query->where('quantity', '<=', $this->filters['quantity_max']);
        });

        $query = $query->when($this->filters['color'] ?? null, function ($query) {
            $colors = explode(',', $this->filters['color']);
            $query->whereIn('color', $colors);
        });

        $query = $query->when($this->filters['size'] ?? null, function ($query) {
            $sizes = explode(',', $this->filters['size']);
            $query->whereIn('size', $sizes);
        });

        $query->when($this->filters['search'] ?? null, function ($query) {
            $query->whereHas('product', function ($query) {
                $query->where('name', $this->filters['search']);
            });
        });

        $query->when($this->filters['category_id'] ?? null, function ($query) {
            $query->whereHas('product', function ($query) {
                $query->where('category_id', $this->filters['category_id']);
            });
        });
        $query->when($this->filters['season_id'] ?? null, function ($query) {
            $query->whereHas('product', function ($query) {
                $query->where('season_id', $this->filters['season_id']);
            });
        });
        // sorting
        $query = $this->applySorting($query);
        return $query;
    }
}
