<?php

namespace App\Http\Requests\Produtos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriarProdutoRequest extends FormRequest
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
            'unidade_id' => ['required', 'exists:unidades,id'],
            'classe_id' => ['required'],
            'especie_id' => ['required'],
            'classificacao_abc' => ['nullable', Rule::in(['A','B','C'])],
            'classificacao_xyz' => ['nullable', Rule::in(['X','Y','Z'])],
            'tipo' => ['required', Rule::in(['normal','re_processado','consignado'])],
            'kit' => ['nullable'],
            'mestre' => ['nullable'],
            'generico' => ['nullable'],
            'opme' => ['nullable'],
        ];
    }
}
