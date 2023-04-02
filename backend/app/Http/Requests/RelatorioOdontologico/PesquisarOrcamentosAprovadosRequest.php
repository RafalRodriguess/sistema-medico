<?php

namespace App\Http\Requests\RelatorioOdontologico;

use Illuminate\Foundation\Http\FormRequest;

class PesquisarOrcamentosAprovadosRequest extends FormRequest
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
            'negociadores.*' => ['nullable', 'exists:instituicao_usuarios,id'],
        ];
    }
}
