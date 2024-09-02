<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Order extends Model
{
    use HasFactory;

    protected $fillable = ['order_date', 'total', 'customer_name'];


    /**
    * Acciones de ejecucion
    *
    */
    protected static function boot()
    {
        parent::boot();

        /**
         * Realiza la accion de regresar el stock a productQuantity cuando se elimina una order
         *
         * @param array $order
         */
        static::deleting(function ($order) {
            foreach ($order->orderDetails as $orderDetail) {
                $productQ = ProductQuantity::where('product_id', $orderDetail->product_id)->first();
                if ($productQ) {
                    $productQ->quantity += $orderDetail->quantity;
                    $productQ->save();
                }
            }
        });
    }
 
    /**
    * Relacion con OrderDetail
    *
    */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
    * Relacion con cliente
    *
    */
    public function customers(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}
