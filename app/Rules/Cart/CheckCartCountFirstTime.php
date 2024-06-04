<?php

namespace App\Rules\Cart;

use Closure;
use App\Models\ProductSize;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckCartCountFirstTime implements ValidationRule
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
            if ($value <= 0 || $ProductSize->quantity < $value) {
                $fail('Can Not Add Cart');
            }
        } catch (\Throwable $th) {
            $fail('Can Not Add Cart');
        }
    }
}
