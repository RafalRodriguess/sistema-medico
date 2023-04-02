<?php

namespace App\Http\Requests\RelatorioOdontologico;

use App\ContaReceber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PesquisarRepasseOdontologicoRequest extends FormRequest
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
            'prestadores.*' => ['required', 'exists:prestadores,id'],
            'procedimentos.*' => ['required', 'exists:procedimentos,id'],
            'convenios.*' => ['required', 'exists:convenios,id'],
            'formas_pagamento.*' => ['required', Rule::in(ContaReceber::formas_pagamento())],
        ];
    }
}
