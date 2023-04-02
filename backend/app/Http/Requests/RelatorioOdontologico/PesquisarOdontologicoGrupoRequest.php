<?php

namespace App\Http\Requests\RelatorioOdontologico;

use App\ContaPagar;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PesquisarOdontologicoGrupoRequest extends FormRequest
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
            'data_inicio' => ['required'],
            'data_fim' => ['required'],
            'contas.*' => ['required', 'exists:contas,id'],
            'negociadores.*' => ['required', 'exists:instituicao_usuarios,id'],
            'avaliadores' => ['nullable', 'exists:prestadores,id'],
            'formas_pagamento.*' => ['required', Rule::in(ContaPagar::formas_pagamento())],
            'convenios' => ['nullable', 'exists:convenios,id'],
            'grupos.*' => ['required', 'exists:grupos_procedimentos,id'],
            'procedimentos.*' => ['nullable', 'exists:procedimentos,id'],
        ];
    }
}
