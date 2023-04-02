<?php

namespace App\Http\Requests\AtestadoPaciente;

use Illuminate\Foundation\Http\FormRequest;

class CriarAtestadoPacienteRequest extends FormRequest
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
            'obs_atestado' => ['required'],
            'atestado_id' => ['nullable', 'exists:atestados_paciente,id'],
            'compartilhado' => ['nullable'],
        ];
    }
}
