<?php

namespace App\Models;

use App\Observers\TransactionObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transcation extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'amount',
        'note',
        'order_id',
    ];

    protected static function booted()
    {
        self::observe(TransactionObserver::class);
    }


    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
