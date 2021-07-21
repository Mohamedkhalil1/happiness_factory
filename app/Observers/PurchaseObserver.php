<?php

namespace App\Observers;

use App\Models\Purchase;

class PurchaseObserver
{

    public function created(Purchase $purchase)
    {
        $ore = $purchase->ore;
        $ore->quantity += $purchase->quantity;
        $ore->save();
    }


    public function updated(Purchase $purchase)
    {
        $ore = $purchase->ore;
        $ore->quantity -= $purchase->getOriginal('quantity');
        $ore->quantity += $purchase->quantity;
        $ore->save();
    }


    public function deleted(Purchase $purchase)
    {
        $ore = $purchase->ore;
        $ore->quantity -= $purchase->getOriginal('quantity');
        $ore->save();
    }

}
