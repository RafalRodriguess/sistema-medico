<?php

namespace App\Http\Requests\Faturamento;

use App\Faturamento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriarFaturamentoRequest extends FormRequest
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
            'tipo' => ['required', Rule::in(Faturamento::tipoValor())],
            'tipo_tiss' => ['required', Rule::in(Faturamento::tipoTISSValor())],
        ];
    }
}
