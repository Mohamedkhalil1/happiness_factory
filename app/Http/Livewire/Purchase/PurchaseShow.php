<?php

namespace App\Http\Livewire\Purchase;

use App\Models\Purchase as PurchaseModel;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseShow extends Component
{
    use WithPagination;

    public string $pageTitle;
    public PurchaseModel $purchase;
    protected $transfers;
    protected $provider;
    public bool $activeCalculations = false;
    public bool $activeTransfers = false;
    public bool $activeProvider = false;


    public function mount($id)
    {
        $this->purchase = PurchaseModel::findOrFail($id);
        $this->pageTitle = 'Purchase - ' . $id;
        $this->activeProvider = true;
        $this->transfers = $this->purchase->transfers()->paginate();
    }


    public function active($tab)
    {
        $this->activeCalculations = $this->activeTransfers = $this->activeProvider = false;
        switch ($tab) {
            case '':
                break;
            case 'calculations':
                $this->activeCalculations = true;
                break;
            case 'transfers':
                $this->activeTransfers = true;
                break;
            case 'provider':
                $this->activeProvider = true;
                break;
        }
    }

    public function render()
    {
        if (!$this->transfers) {
            $this->transfers = $this->purchase->transfers()->paginate();
        }
        if (!$this->provider) {
            $this->provider = $this->purchase->provider;
        }
        return view('livewire.purchase.purchase-show', [
            'model'     => $this->purchase,
            'transfers' => $this->transfers,
            'provider'  => $this->provider,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
