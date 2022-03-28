<?php

namespace App\Rules;

use App\Models\Product;
use App\Models\order_item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;

class StockValidationOrder implements Rule
{
    public $productId;
    public $orderId;
    public $message= "";
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($productId , $orderId)
    {
        $this->productId = $productId;
        $this->orderId = $orderId;
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
        $product = Product::where([ ['id', $this->productId], ['stock', '>=', $value], ['status', true] ])->first();
        if($product)
        {
            $order_item = order_item::where([ ['order_id', $this->orderId], ['product_id', $product->id] ])->first();
            if($order_item)
            {
                if($order_item->count + $value <= $product->stock)
                {
                    return true;
                }
                $this->message="the stock can not grap it";
                return false;
            }
            return true;
        }
        $this->message="plz chek product";
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
