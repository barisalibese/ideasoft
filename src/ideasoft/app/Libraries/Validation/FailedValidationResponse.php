<?php


namespace App\Libraries\Validation;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

trait FailedValidationResponse
{
    protected function failedValidation(Validator $validator)
    {
        $response = (new JsonResponse(['errors' => $validator->errors()->getMessageBag()->all()],400))->send();
        throw new HttpResponseException($response);
    }
}
