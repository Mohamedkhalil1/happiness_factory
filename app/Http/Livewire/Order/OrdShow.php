<?php

namespace App\Http\Livewire\Order;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrdShow extends Component
{
    use WithPagination;

    public string $pageTitle;
    public Order $order;
    protected $products;
    protected $transactions;
    protected $client;
    public bool $activeCalculations = false;
    public bool $activeProducts = false;
    public bool $activeTransactions = false;
    public bool $activeClient = false;


    public function mount($id)
    {
        $this->order = Order::find($id);
        $this->pageTitle = 'Order - ' . $id;
        $this->activeClient = true;
        $this->products = $this->order->inventories()
            ->whereHas('orderDetails', function ($query) use ($id) {
                $query->where('order_id', $id);
            })
            ->with('product')->paginate();

        $this->transactions = $this->order->transactions()->paginate();
    }


    public function active($tab)
    {
        $this->activeCalculations = $this->activeProducts = $this->activeTransactions = $this->activeClient = false;
        switch ($tab) {
            case '':
                break;
            case 'calculations':
                $this->activeCalculations = true;
                break;
            case 'products':
                $this->activeProducts = true;
                break;
            case 'transactions':
                $this->activeTransactions = true;
                break;
            case 'client':
                $this->activeClient = true;
                break;
        }
    }

    public function render()
    {
        if (!$this->products) {
            $this->products = $this->order->inventories()->with('product')->paginate();
        }
        if (!$this->transactions) {
            $this->transactions = $this->order->transactions()->paginate();
        }
        if (!$this->client) {
            $this->client = $this->order->client;
        }

        return view('livewire.order.ord-show', [
            'model'        => $this->order,
            'products'     => $this->products,
            'transactions' => $this->transactions,
            'client'       => $this->client,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
