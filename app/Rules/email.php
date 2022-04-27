<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\User;

class email implements Rule
{
    protected $valiedEmail=false;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        // //
        //     # code...
            $this->valiedEmail= User::where('id', $id)->first();;
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

// use Illuminate\Support\Facades\Validator;
// $rules = ['name' => 'unique:users,name'];

$email= User::where('email', $value)->doesntExist() ;
// dd($this->valiedEmail ==  $value , $email);
        if ($this->valiedEmail->email ==  $value || $email) {
            return true ;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The email is exist.';
    }
}
