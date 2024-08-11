<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\handlingResponseRequest;
use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends handlingResponseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'firstname'=> 'required|max:255',
            'lastname'=> 'required|max:255',
            'email'=> 'required|max:255',
            'message'=> 'required',
        ];
    }
}
