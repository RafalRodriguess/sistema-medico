<?php

namespace App\Http\Requests\ConveniosPlanos;

use Illuminate\Foundation\Http\FormRequest;

class EditarRequestConvenioPlano extends FormRequest
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
            'nome' => ['required'],
            'descricao' => ['nullable'],
            'paga_acompanhante' => ['in:on','nullable'],
            'validade_indeterminada' => ['in:on','nullable'],
            'senha_guia_obrigatoria' => ['in:1,0','nullable'],
            'valida_guia' => ['in:1,0','nullable'],
            'permissao_internacao' => ['in:on','nullable'],
            'permissao_emergencia' => ['in:on','nullable'],
            'permissao_home_care' => ['in:on','nullable'],
            'permissao_ambulatorio' => ['in:on','nullable'],
            'permissao_externo' => ['in:on','nullable'],
            'regra_cobranca_id' => ['nullable', 'exists:regras_cobranca,id']
        ];
    }
}
