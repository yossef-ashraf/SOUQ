<?php

namespace App\Rules;

use App\Models\Cart;
use Illuminate\Contracts\Validation\Rule;

class CheckSoldOut implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
            $cartitems=Cart::where('user_id', auth()->user()->id)->with(['products'])->get();
            foreach ($cartitems as $item) {
                if ($item->soldout == 1 ) {
                    return false;
                }
            }

            return true ;

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
        return 'you have product sold out .';
    }
}
