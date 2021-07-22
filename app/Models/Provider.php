<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'address',
        'phone',
        'worked_date',
        'details',
    ];

    public function getTotalAmountAttribute()
    {
        return $this->purchases->sum('total_amount') + $this->accessories->sum('amount');
    }

    public function getPaidAmountAttribute()
    {
        return $this->purchases->sum('paid_amount') + $this->accessories->sum('amount');
    }

    public function getRemainAttribute()
    {
        return $this->purchases->sum('remain');
    }

    public function accessories(): HasMany
    {
        return $this->hasMany(Accessory::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
}
