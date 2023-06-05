<?php

namespace App\Rules;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;

class StokeOrder implements Rule
{
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
try {
    $cartitems=Cart::where('user_id', auth()->user()->id)->with(['products'])->get();
    foreach ($cartitems as $item) {
        if ($item->products->quantity < $item->count ) {
            $this->message .= " product ". $item->products->name ." have not stock for your order ";
            if ($item->products->quantity == 0 ) {
                $item->update([
                    'soldout' => 1
            ]);
            $this->message .= " product ". $item->products->name ." is sold out ";

            }
        }
    }

    if ($this->message) {
        return false;
    }
    return true ;

} catch (\Throwable $th) {
    //throw $th;
    $this->message = "error in rule";
    return false;
}




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
