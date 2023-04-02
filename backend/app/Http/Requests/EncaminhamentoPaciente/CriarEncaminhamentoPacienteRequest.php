<?php

namespace App\Http\Requests\EncaminhamentoPaciente;

use Illuminate\Foundation\Http\FormRequest;

class CriarEncaminhamentoPacienteRequest extends FormRequest
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
            'obs_encaminhamento' => ['required'],
            'encaminhamento_id' => ['nullable', 'exists:encaminhamentos_paciente,id'],
        ];
    }
}
