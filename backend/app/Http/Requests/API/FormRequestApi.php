<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FormRequestApi extends FormRequest
{

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors()->all(), 422));
    }

}
