<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'address',
        'phone',
        'worked_date',
        'details',
        'avatar',
    ];

    public function getTotalAmountAttribute()
    {
        return $this->orders->sum('amount_after_discount');
    }


    public function getPaidAmountAttribute()
    {
        return $this->orders->sum('amount_after_discount') - $this->orders->sum('remain');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
