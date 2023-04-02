<?php

namespace App\Http\Requests\Internacao;

use Illuminate\Foundation\Http\FormRequest;

class RealizaAltaRequest extends FormRequest
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
            'id' => ['required'],
            'data_alta' => ['required'],
            'motivo_alta_id' => ['required'],
            'infeccao_alta' => ['required'],
            'procedimento_alta_id' => ['required'],
            'obs_alta' => ['nullable'],
            'especialidade_alta_id' => ['nullable'],
        ];
    }
}
