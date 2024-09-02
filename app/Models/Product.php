<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected static $quantity = '0';
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'provider_name',
    ];

    /**
    * Obtiene los productos cuyo stock sea mayor a cero
    *
    */
    public static function withStock()
    {
        return self::whereHas('quantities', function ($query) {
            $query->where('quantity', '>', 0);
        })->pluck('name', 'id');
    }

    /**
    * Acciones del modelo
    *
    */
    protected static function boot()
    {
        parent::boot();
        
        /**
         * Acciones en crear
         *
         * @param array $product
         */
        static::creating(function ($product){
            self::$quantity = $product->quantity;
            unset($product->quantity);
        });

        /**
         * Acciones en guardar
         *
         * @param array $product
         */
        static::saved(function ($product) {
            $product->quantities()->updateOrCreate(
                ['product_id' => $product->id],
                ['quantity' => self::$quantity]
            );
        });

        /**
         * Acciones en editar
         *
         * @param array $product
         */
        static::updating(function ($product) {
            self::$quantity = $product->quantity;
            unset($product->quantity);
        });

        /**
         * Acciones en actualizar
         *
         * @param array $product
         */
        static::updated(function ($product) {
            $product->quantities()->updateOrCreate(
                ['product_id' => $product->id],
                ['quantity' => self::$quantity]
            );
        });
    }

    /**
     * Relación con product
     *
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    /**
     * Relación con productQuantities
     *
     */
    public function quantities(): HasMany
    {
        return $this->hasMany(ProductQuantity::class);
    }

     /**
    * Relacion con Providers
    *
    */
    public function providers(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }


    
}
