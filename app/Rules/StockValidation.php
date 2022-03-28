<?php

namespace App\Rules;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StockValidation implements Rule
{
    public $productId;
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
        $product = Product::where([ ['id', $this->productId], ['stock', '>=', $value] , ['status', true] ])->first();
        if($product)
        {
            $cart = Cart::where([ ['user_id', Auth::user()->id], ['product_id', $product->id] ])->first();
            if($cart)
            {
                if($cart->count + $value <= $product->stock)
                {
                    return true;
                }
                return false;
            }

            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Stock Not found';
    }
}
