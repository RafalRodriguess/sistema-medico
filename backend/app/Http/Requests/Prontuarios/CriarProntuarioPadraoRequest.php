<?php

namespace App\Http\Requests\Prontuarios;

use Illuminate\Foundation\Http\FormRequest;

class CriarProntuarioPadraoRequest extends FormRequest
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
            'prontuario_id' => ['nullable', 'exists:prontuarios_paciente,id'],
            'compartilhado' => ['nullable'],
            'queixa_principal' => ['nullable'],
            'h_m_a' => ['nullable'],
            'h_p' => ['nullable'],
            'h_f' => ['nullable'],
            'hipotese_diagnostica' => ['nullable'],
            'conduta' => ['nullable'],
            'exame_fisico' => ['nullable'],
            'obs' => ['nullable'],
            'cid' => ['nullable'],
        ];
    }
}
