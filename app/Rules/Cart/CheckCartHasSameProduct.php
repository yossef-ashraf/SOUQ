<?php

namespace App\Rules\Cart;

use Closure;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckCartHasSameProduct implements ValidationRule
{
    public $id ;
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $cart= Cart::where([['product_id', $this->id], ['user_id', Auth::user()->id] ])->first();
            if ($cart) {
                $fail('Can Not Add Cart');
            }
        } catch (\Throwable $th) {
            //throw $th;
            $fail('Can Not Add Cart');
        }


    }
}
