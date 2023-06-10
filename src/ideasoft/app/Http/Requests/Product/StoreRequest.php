<?php

namespace App\Http\Requests\Product;

use App\Libraries\Validation\FailedValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    use FailedValidationResponse;
    public function rules(): array
    {
        return [
            'name' => 'string|required|min:3',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'stock' => 'integer|required',
            'categories' => [
                'required',
                /*'confirmed',*/
                'array',
            ],
        ];
    }
}
