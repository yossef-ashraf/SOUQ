<?php

namespace App\Rules;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;

class HaveProduct implements Rule
{
    public $id ;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id =$id ;
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
            $cart= Cart::where([['product_id', $this->id], ['user_id', Auth::user()->id] ])->first();
            if($cart)
            {
            return false ;
            }
            return true ;
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
        return 'The Already You Have In Cart .';
    }
}
