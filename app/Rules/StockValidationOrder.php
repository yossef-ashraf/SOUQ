<?php

namespace App\Rules;

use App\Models\cart;
use App\Models\Product;
use App\Models\order_item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;

class StockValidationOrder implements Rule
{
    public $err=0;
    public $message= "";
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        // dd('hi');

        $cartitems=cart::where('user_id', auth()->user()->id)->with('products')->get();
        foreach ($cartitems as $item) {
            if ($item->products->stock < $item->count ) {
                $this->message .= " product ". $item->products->name ." have not stock for your order ";
            }
            if (!$item->products->status ) {
                $this->message .= " product ". $item->products->name ." can not be in your order ";
            }
        }
        if ($this->message) {
            return false;
        }
        return true ;

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
