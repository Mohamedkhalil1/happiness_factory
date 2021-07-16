<?php

namespace App\Models;

use App\Enums\AmountType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'with',
        'with_value',
        'date',
        'employee_id',
        'total',
    ];
    protected $appends = ['total'];


    public function getTotalAttribute()
    {
        if (!isset($this->attributes['with'])) {
            return 0;
        }
        if ($this->attributes['with'] == AmountType::NORMAL) return $this->amount;
        else if ($this->attributes['with'] == AmountType::INCREASE) return $this->amount + $this->with_value;
        else if ($this->attributes['with'] == AmountType::DECREASE) return $this->amount - $this->with_value;
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
