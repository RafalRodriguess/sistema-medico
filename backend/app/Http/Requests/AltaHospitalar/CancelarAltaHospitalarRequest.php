<?php

namespace App\Http\Requests\AltaHospitalar;

use Illuminate\Foundation\Http\FormRequest;

class CancelarAltaHospitalarRequest extends FormRequest
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
            'motivo_cancel_alta' => ['required'],
        ];
    }
}
