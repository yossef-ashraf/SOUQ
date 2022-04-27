<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class images implements Rule
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
foreach ($value as $image ) {
    # code...
    // dd( $image->extension() !== "png" &&  $image->extension() !== "jpg" &&  $image->extension() !== "jpeg");
    if ( $image->extension() !== "png" &&  $image->extension() !== "jpg" &&  $image->extension() !== "jpeg") {
        return false;
    }

}
return true;
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
