<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
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
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
