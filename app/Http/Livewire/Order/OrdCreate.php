<?php

namespace App\Http\Livewire\Order;

use App\Http\Livewire\Datatable\WithCachedRows;
use App\Http\Livewire\Datatable\WithPerPagePagination;
use App\Http\Livewire\Datatable\WithSorting;
use App\Http\Livewire\InventoriesFilters\WithInventoriesFilters;
use App\Models\Client;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Season;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OrdCreate extends Component
{
    use WithPerPagePagination, WithCachedRows, WithInventoriesFilters, WithSorting;

    public string $pageTitle = 'Order Details';
    public $order;
    public array $quantities = [];
    public $orderInventories = [];
    public bool $showInventories = true;
    public bool $showAdvancedSearch = false;
    public $seasons = [];
    public $categories = [];
    public $products = [];
    public $clients = [];

    public function mount()
    {
        $this->order = new Order();
        $this->order->discount = 0;
    }

    public function rules(): array
    {
        return [
            'order.date'                   => 'required|date',
            'order.address'                => 'nullable|string|max:255',
            'order.amount_before_discount' => 'required|numeric|min:0|max:999999',
            'order.discount'               => 'nullable|numeric|min:0|max:999999',
            'order.amount_after_discount'  => 'required|numeric|min:0|max:999999',
            'order.remain'                 => 'nullable|numeric|min:0|max:999999',
            'order.client_id'              => 'required|exists:clients,id',
        ];
    }

    public function updatedOrderDiscount($value)
    {
        $this->calculateAfterDiscount();
    }

    public function showOrHideInventories($hideOrShow)
    {
        $this->showInventories = $hideOrShow;
    }

    public function addInventoryToOrder($inventoryId)
    {
        if (isset($this->orderInventories[$inventoryId])) {
            $this->notify('inventory is already exist, remove it before add again', '#dc3545');
            return;
        }
        $inventory = Inventory::find($inventoryId);
        if (!$inventory) {
            $this->notify('inventory is not found', '#dc3545');
            return;
        }
        $this->validate([
            "quantities.$inventoryId" => "required|integer|max:$inventory->quantity",
        ]);
        $this->orderInventories[$inventoryId] = [
            'inventory_id' => $inventoryId,
            'quantity'     => $this->quantities[$inventoryId],
            'price'        => $inventory->price,
        ];
        $this->order->amount_before_discount += $inventory->price * $this->quantities[$inventoryId];
        $this->calculateAfterDiscount();
        $this->notify('inventory is added to order');
    }

    public function removeItem($inventoryId)
    {
        if ($this->quantities[$inventoryId]) {
            if (isset($this->orderInventories[$inventoryId])) {
                unset($this->orderInventories[$inventoryId]);
            }
            if ($this->order->amount_before_discount) {
                $inventory = Inventory::find($inventoryId);
                if (!$inventory) {
                    $this->notify('inventory is not found', '#dc3545');
                    return;
                }
                $this->order->amount_before_discount -= $inventory->price * $this->quantities[$inventoryId];
                $this->calculateAfterDiscount();
            }

            unset($this->quantities[$inventoryId]);
        }
        $this->notify('inventory is removed successfully', '#dc3545');
    }

    public function toggleAdvancedSearch()
    {
        $this->showAdvancedSearch = !$this->showAdvancedSearch;
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->applyPaginatation($this->rowsQuery);
        });
    }

    public function notify($message = 'saved', $color = "#4fbe87")
    {
        $this->dispatchBrowserEvent('notify', ['message' => $message, 'color' => $color]);
    }

    public function addOrder()
    {
        $this->validate();
        DB::beginTransaction();
        $this->order->remain = $this->order->amount_after_discount;
        $this->order->save();
        foreach ($this->orderInventories as $orderInventory) {
            //decrement quantity
            $inventory = Inventory::find($orderInventory['inventory_id']);
            $inventory->quantity -= $orderInventory['quantity'];
            $inventory->save();
            //save sub orders
            $this->order->orderInventories()->create($orderInventory);
        }
        DB::commit();
        return redirect()->route('orders.index');
    }

    private function calculateAfterDiscount()
    {
        if ($this->order->discount == 0) {
            $this->order->amount_after_discount = $this->order->amount_before_discount;

        } elseif ($this->order->amount_before_discount == 0) {
            $this->order->amount_after_discount = 0;
        } elseif ($this->order->discount > 0 && $this->order->amount_before_discount > 0) {
            $this->order->amount_after_discount = $this->order->amount_before_discount -
                ($this->order->amount_before_discount * $this->order->discount / 100);
        }
    }

    public function render()
    {
        if (!$this->seasons) {
            $this->seasons = Season::select('id', 'name')->get();
        }
        if (!$this->categories) {
            $this->categories = ProductCategory::select('id', 'name')->get();
        }
        if (!$this->products) {
            $this->products = Product::select('id', 'name')->get();
        }

        if (!$this->clients) {
            $this->clients = Client::select('id', 'name')->get();
        }

        return view('livewire.order.ord-create', [
            'inventories' => $this->rows,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
