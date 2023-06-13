<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
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
