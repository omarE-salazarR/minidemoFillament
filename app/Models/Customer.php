<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'identification',
        'phone',
        'email',
    ];

    /**
    * Relación con Order
    *
    */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

}
