<?php

namespace App\Rules;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;

class IfCartHasError implements Rule
{
    public $id ;
    public $message= "";
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id)
    {

        $this->id = $id;

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

        try {
            $cart= Cart::where([['id', $this->id], ['user_id', Auth::user()->id] ])->first();
            if ($cart) {
            if ($cart->products->quantity < $cart->count ) {
                if ($cart->products->quantity == 0 ) {
                    $cart->update([
                        'soldout' => 1
                ]);
                    $this->message = 'The Product is sold out plase delete from your cart.';
                }else {
                    $cart->update([
                        'count' => 1,
                        'soldout' => 0
                ]);
                $this->message = 'The Product don,t have your quantity , will update now in your cart.';
                }

                return false;
                 }
                 return false;
                }
                return true;
        } catch (\Throwable $th) {
            //throw $th;
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
        return $this->message ;
    }
}
