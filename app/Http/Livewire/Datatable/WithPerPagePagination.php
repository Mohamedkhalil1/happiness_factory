<?php

namespace App\Http\Livewire\Datatable;

use Livewire\WithPagination;

trait WithPerPagePagination
{
    use WithPagination;

    public int $perPage = 10;
    private int $maxPerPage = 50;
    private int $minPerPage = 10;
    public function initializeWithPerPagePagination()
    {
        $this->perPage = session()->get('perPage', $this->perPage);
    }

    public function updatedPerPage($value)
    {
        $this->perPage = max(min($this->perPage,$this->maxPerPage),$this->minPerPage);
        session()->put('perPage', $value);
        $this->resetPage();
        $this->selectPageRows();

    }

    public function applyPaginatation($query)
    {
        return $query->paginate($this->perPage);
    }
}
