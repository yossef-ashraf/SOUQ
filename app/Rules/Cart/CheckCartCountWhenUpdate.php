<?php

namespace App\Rules\Cart;

use Closure;
use App\Models\Cart;
use App\Models\ProductSize;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckCartCountWhenUpdate implements ValidationRule
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
            $ProductSize= ProductSize::where('id',  $this->id)->first();
            $cart= Cart::where([['product_size_id', $this->id], ['user_id', Auth::user()->id] ])->with(['product_size'])->first();

            if ($cart->product_size->quantity > $cart->count ) {
                    $cart->update([
                        'soldout' => 0
                ]);
            }
            
            if ($value <= 0 || $ProductSize->quantity < ($value + $cart->count)) {
                $fail('Can Not Add Cart');
            }
        } catch (\Throwable $th) {
            $fail('Can Not Add Cart');
        }
    }
}
