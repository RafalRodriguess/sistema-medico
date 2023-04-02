<?php

namespace App\Http\Requests\RelatorioPaciente;

use Illuminate\Foundation\Http\FormRequest;

class CriarRelatorioPacienteRequest extends FormRequest
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
            'obs_relatorio' => ['required'],
            'relatorio_id' => ['nullable', 'exists:relatorios_paciente,id'],
            'compartilhado' => ['nullable'],
        ];
    }
}
