<?php

namespace App\Http\Requests\API\AuthController;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SelfRevokeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'device.manufacturer' => ['required', 'string'],
            'device.model' => ['required', 'string'],
            'device.platform' => ['required', 'string'],
            'device.uuid' => ['required_unless:device.platform,browser', 'string'],
        ];
    }
}
