<?php

namespace App\Http\Livewire\Datatable;

trait WithBulkActions
{
    public bool $selectedPage = false;
    public bool $selectedAll = false;
    public array $selected = [];

//    IN Livewire 2.5.4 beforeRender
//    public function initializeWithBulkActions()
//    {
//        $this->beforeRender(function () {
//            if ($this->selectedAll) {
//                $this->selectPage();
//            }
//        });
//    }


    public function updatedSelected()
    {
        $this->selectedAll = false;
        $this->selectedPage = false;
    }

    public function selectedAll()
    {
        $this->selectedAll = true;
    }

    public function updatedSelectedPage($value): array
    {
        if (!$value) {
            return $this->selected = [];
        }
        return $this->selectPageRows();
    }

    public function selectPageRows()
    {
        if($this->selectedPage || $this->selectedAll){
            return $this->selected = $this->rows->pluck('id')->map(fn($id) => (string)$id)->toArray();
        }
       return;
    }

    public function getSelectedRowsQuery()
    {
        return (clone $this->rowsQuery)
            ->unless($this->selectedAll, fn($query) => $query->whereKey($this->selected));
    }

}
