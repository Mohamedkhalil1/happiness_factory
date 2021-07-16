<?php

namespace App\Http\Livewire\Datatable;

trait WithSorting
{
    public $sortField;
    public string $sortDirection = 'asc';

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field
            ? $this->reverseSort()
            : 'asc';
        $this->sortField = $field;
    }

    public function applySorting($query)
    {
        if (!$this->sortField) {
            return $query;
        }
        return $query->orderBy($this->sortField, $this->sortDirection);
    }

    public function reverseSort(): string
    {
        return $this->sortDirection === 'asc'
            ? 'desc'
            : 'asc';
    }
}
