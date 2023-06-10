<?php

namespace App\Http\Requests\Customer;


use App\Libraries\Validation\FailedValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    use FailedValidationResponse;
    public function attributes()
    {
        return [
            'name' => 'name',
            'email' => 'email',
            'password' => 'password'
        ];
    }
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:40|min:3|regex:/^([^&\'"<>]*)$/',
            'email' => 'required|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'password' => [
                'required',
                /*'confirmed',*/
                'string',
                'regex:/[a-zA-Z]/', // must contain at least one lowercase letter
                'regex:/[!@#$%^&*_0-9]/', // must contain at least one digit or one special char
                'min:6'
            ],
        ];
    }
}
