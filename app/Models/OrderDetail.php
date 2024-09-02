<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ProductQuantity;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'product_name', 'quantity', 'total'];
    protected static $quantity = 0;
    protected static $quantityInd = 0;

    /**
    * Acciones de ejecución
    *
    */
    protected static function boot()
    {
        parent::boot();

        /**
         * Realiza la accion de determinar si se va a restar del stock o se va a regresar cuando se esta guardando el item
         *  opera para crear y editar
         *
         * @param array $orderDetail
         */
        static::saving(function ($orderDetail) {
            if ($orderDetail->exists) {
                $originalQuantity = $orderDetail->getOriginal('quantity');
                if ($originalQuantity > $orderDetail->quantity) {
                    self::$quantityInd = 2;
                    self::$quantity = $originalQuantity - $orderDetail->quantity;
                } elseif ($originalQuantity < $orderDetail->quantity) {
                    self::$quantity = $orderDetail->quantity - $originalQuantity;
                    self::$quantityInd = 1;
                } else {
                    self::$quantityInd = 3;    
                }
            }
        });

        /**
         * Realiza la accion definida en la funcion saving
         *
         * @param array $orderDetail
         */
        static::saved(function ($orderDetail) {
            $productQ = ProductQuantity::where('product_id', $orderDetail->product_id)->first();
            if ($productQ) {
                if (isset(self::$quantityInd) && self::$quantityInd > 0) {
                    if (self::$quantityInd == 1) { // diferencia se suma
                        $productQ->quantity -= self::$quantity; 
                    } elseif (self::$quantityInd == 2) { // diferencia se resta
                        $productQ->quantity += self::$quantity; 
                    }
                } else {
                    $productQ->quantity -= $orderDetail->quantity;
                }
                $productQ->save();
            }
        });

        /**
         * Realiza la accion de regresar el stock a productQuantity cuando 
         * se elimina un item de la orden
         *
         * @param array $orderDetail
         */
        static::deleting(function ($orderDetail) {
            $productQ = ProductQuantity::where('product_id', $orderDetail->product_id)->first();
            if ($productQ) {
                $productQ->quantity += $orderDetail->quantity;
                $productQ->save();
            }
        });
    }


    /**
    * Relación con Order
    *
    */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
    * Relación con product
    *
    */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
