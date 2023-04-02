<?php

namespace App\Http\Requests\GrupoFaturamento;

use App\GrupoFaturamento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriarGrupoFaturamentoRequest extends FormRequest
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
            'descricao' => ['required'],
            'tipo' => ['required', Rule::in(GrupoFaturamento::tipoValores())],
            'val_grupo_faturamento' => ['nullable', 'boolean'],
            'rateio_nf' => ['nullable', 'boolean'],
            'incide_iss' => ['nullable', 'boolean'],
        ];
    }
}
