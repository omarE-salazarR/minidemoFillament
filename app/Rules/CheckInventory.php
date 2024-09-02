<?php

namespace App\Rules;
use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;

class CheckInventory implements Rule
{
    protected $productId;
    protected $quantity;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->quantity = $value;
        $product = Product::find($this->productId);

        return $product && $product->quantity >= $this->quantity;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La cantidad solicitada no está disponible en el inventario.';
    }
}
