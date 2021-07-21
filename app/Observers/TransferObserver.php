<?php

namespace App\Observers;

use App\Models\Transfer;

class TransferObserver
{

    public function created(Transfer $transfer)
    {
        $purchase = $transfer->purchase;
        $purchase->remain -= $transfer->amount;
        $purchase->paid_amount += $transfer->amount;
        $purchase->getStatus();
        $purchase->save();
    }

    public function updated(Transfer $transfer)
    {
        $purchase = $transfer->purchase;
        $purchase->remain += $transfer->getOriginal('amount');
        $purchase->paid_amount -= $transfer->getOriginal('amount');
        $purchase->remain -= $transfer->amount;
        $purchase->paid_amount += $transfer->amount;
        $purchase->getStatus();
        $purchase->save();
    }

}
