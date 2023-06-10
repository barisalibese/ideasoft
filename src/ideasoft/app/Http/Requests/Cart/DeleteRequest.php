<?php

namespace App\Http\Requests\Cart;

use App\Libraries\Validation\FailedValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{
    use FailedValidationResponse;

    public function rules(): array
    {
        $rules=['all'=>'boolean'];
        if (!$this->attributes->has('all') || !$this->attributes->get('all')) {
            $rules['products'] = 'array|required';
            $rules['products.*.id'] = 'int|required';
            $rules['products.*.all'] = 'boolean';
            if (!$this->attributes->has('products.*.all') || !$this->attributes->get('products.*.all')) {
                $rules['products.*.quantity'] = 'int|required_if:products.*.all,==,0|integer';
            }
        }
        return $rules;
    }
}
