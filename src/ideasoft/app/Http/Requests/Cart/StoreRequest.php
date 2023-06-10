<?php

namespace App\Http\Requests\Cart;

use App\Libraries\Validation\FailedValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    use FailedValidationResponse;

    public function rules(): array
    {
        return [
            'products' => 'array|required',
            'products.*.id' => 'int|required',
            'products.*.quantity' => 'int|required',
        ];
    }
}
