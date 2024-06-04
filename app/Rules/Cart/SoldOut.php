<?php

namespace App\Rules\Cart;

use Closure;
use App\Models\Cart;
use Illuminate\Contracts\Validation\ValidationRule;

class SoldOut implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {

            $cartitems=Cart::where('user_id', auth()->user()->id)->with(['product_size'])->get();
            foreach ($cartitems as $item) {
                if ($item->product_size->quantity >= $item->count ) {
                    $item->update([
                        'soldout' => 0
                ]);
                }
            }
            foreach ($cartitems as $item) {
                if ($item->soldout == 1 ) {
                     $fail('Can Not Add Cart');
                }
            }

        } catch (\Throwable $th) {
            //throw $th;
             $fail('Can Not Add Cart');
        }
    }
}
