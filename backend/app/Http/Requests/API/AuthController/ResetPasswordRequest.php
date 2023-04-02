<?php

namespace App\Http\Requests\API\AuthController;

use App\Http\Requests\API\FormRequestApi;
use Illuminate\Validation\Rule;

class ResetPasswordRequest extends FormRequestApi
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
            'codigo' => 'required|exists:password_resets,token',
            'password' => 'required|string|min:8|confirmed'
        ];
    }

}
