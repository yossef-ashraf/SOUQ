<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class image implements Rule
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
            if ($value->extension() == "png" || $value->extension() == "jpg" || $value->extension() == "jpeg") {
                // dd("hi");
                return true;
            }
            return false ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'extension not supported only support [png,jpg,jpeg].';
    }
}
