<?php

namespace App\Rules\Promo;

use Closure;
use App\Models\Promo;
use Illuminate\Contracts\Validation\ValidationRule;

class PromoCode implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $promo = Promo::where('promo', $value)->first();

        //
    }
}
