<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{

    public function deleting(Order $order)
    {
        $inventories = $order->inventories;
        $orderInventories = $order->orderInventories;
        foreach ($orderInventories as $orderInventory) {
            $inventory = $inventories->find($orderInventory->inventory_id);
            $inventory->quantity += $orderInventory->quantity;
            $inventory->save();
        }
    }
}
