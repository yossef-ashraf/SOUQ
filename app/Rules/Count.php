<?php

namespace App\Rules;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;

class Count implements Rule
{
    public $id ;
    public $message;

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
                // dd($cart->count + $value );
                if(($cart->count + $value ) > 0)
                {
                return true;
                }
                elseif (($cart->count + $value ) <= 0)
                {
                    $this->message = 'your cart is deleted' ;
                    $cart->delete();
                    return false;
                }
                $this->message = 'Count be bigger than' ;
                return false;
            }
            $this->message = 'Count be bigger than' ;
            return false;
    } catch (\Throwable $th) {
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
