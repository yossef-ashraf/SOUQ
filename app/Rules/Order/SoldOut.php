<?php

namespace App\Rules\Order;

use Closure;
use App\Models\Cart;
use Illuminate\Contracts\Validation\ValidationRule;

class SoldOut implements ValidationRule
{
    public $message= "";
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $cartitems=Cart::where('user_id', auth()->user()->id)->with(['product_size','product'])->get();

            foreach ($cartitems as $item) {
                if ($item->soldout == 1 ) {
                    $this->message = "You Have Cart Sold Out" ;
                }

                if ($item->product_size->quantity > $item->count ) {
                    $item->update([
                        'soldout' => 0
                ]);
                }
            }
            foreach ($cartitems as $item) {
                if ($item->product_size->quantity < $item->count ) {
                    $this->message .= " product ". $item->product->name ." have not stock for your order ";
                    if ($item->product_size->quantity == 0 ) {
                        $item->update([
                            'soldout' => 1
                    ]);
                    $this->message .= " product ". $item->product->name ." is sold out ";

                    }
                }
            }

            if ($this->message) {
                $fail($this->message);
            }

        } catch (\Throwable $th) {
            //throw $th;
            $this->message = "error in rule";
            $fail($this->message);
        }

    }
}
