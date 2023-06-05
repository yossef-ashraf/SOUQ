<?php

namespace App\Rules;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;

class UpStoke implements Rule
{
    public $id ;
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
    {try {

    $cart= Cart::where([['id', $this->id], ['user_id', Auth::user()->id] ])->first();
    if ($cart) {
    if ($cart->products->quantity >= ($cart->count + $value)) {
        return true;
         }
        }
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
        return 'The Quantity Unavailable now.';
    }
}
