<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];
    protected $appends = [
        'quantity',
    ];

    public function getQuantityAttribute()
    {
        return $this->ores->sum('quantity');
    }

    public function ores(): HasMany
    {
        return $this->hasMany(Ore::class);
    }
}
