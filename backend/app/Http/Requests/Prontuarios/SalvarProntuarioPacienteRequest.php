<?php

namespace App\Http\Requests\Prontuarios;

use Illuminate\Foundation\Http\FormRequest;

class SalvarProntuarioPacienteRequest extends FormRequest
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
            'obs_prontuario' => ['required'],
            'prontuario_id' => ['nullable', 'exists:prontuarios_paciente,id'],
            'compartilhado' => ['nullable'],
        ];
    }
}
