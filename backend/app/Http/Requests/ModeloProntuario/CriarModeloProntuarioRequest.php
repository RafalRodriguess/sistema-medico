<?php

namespace App\Http\Requests\ModeloProntuario;

use Illuminate\Foundation\Http\FormRequest;

class CriarModeloProntuarioRequest extends FormRequest
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
            'instituicao_prestador_id' => ['required', 'exists:instituicoes_prestadores,id'],
            'descricao' => ['required'],
            'modelo' => ['required'],
            'queixa_principal' => ['nullable'],
            'h_m_a' => ['nullable'],
            'h_p' => ['nullable'],
            'h_f' => ['nullable'],
            'hipotese_diagnostica' => ['nullable'],
            'conduta' => ['nullable'],
            'exame_fisico' => ['nullable'],
            'obs' => ['nullable'],
            'cid' => ['nullable'],
            'texto' => ['required_if:modelo,livre'],
        ];
    }
}
