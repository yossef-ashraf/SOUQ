<?php

namespace App\Rules;

use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Contracts\Validation\Rule;

class Stoke implements Rule
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
    {
        try {
            $product= Product::where([['id',  $this->id] ])->first();
            if($product->quantity >= $value )
            {
            return true;
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
