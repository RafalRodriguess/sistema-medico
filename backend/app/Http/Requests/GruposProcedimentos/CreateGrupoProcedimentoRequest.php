<?php

namespace App\Http\Requests\GruposProcedimentos;

use App\GrupoFaturamento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateGrupoProcedimentoRequest extends FormRequest
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
            'principal' => ['nullable', 'boolean'],
            'grupo_faturamento_id' => ['nullable', 'exists:grupos_faturamento,id'],
            'tipo' => ['nullable', Rule::in(GrupoFaturamento::tipoValores())],
        ];
    }
}
