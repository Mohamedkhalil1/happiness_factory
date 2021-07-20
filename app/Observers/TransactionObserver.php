<?php

namespace App\Observers;


use App\Models\Transcation;

class TransactionObserver
{

    public function created(Transcation $transaction)
    {
        $order = $transaction->order;
        $order->remain -= $transaction->amount;
        $order->getStatus();
        $order->save();
    }

    public function updated(Transcation $transaction)
    {
        $order = $transaction->order;
        $order->remain += $transaction->getOriginal('amount');
        $order->remain -= $transaction->amount;
        $order->getStatus();
        $order->save();
    }
}
