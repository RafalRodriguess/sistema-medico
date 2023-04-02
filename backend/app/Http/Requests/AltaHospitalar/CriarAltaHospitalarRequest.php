<?php

namespace App\Http\Requests\AltaHospitalar;

use Illuminate\Foundation\Http\FormRequest;

class CriarAltaHospitalarRequest extends FormRequest
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
            'internacao_id' => ['required'],
            'data_alta' => ['required'],
            'motivo_alta_id' => ['required'],
            'infeccao_alta' => ['required'],
            'declaracao_obito_alta' => ['nullable'],
            'procedimento_alta_id' => ['required'],
            'especialidade_alta_id' => ['required'],
            'obs_alta' => ['nullable'],
        ];
    }
}
