<?php

namespace App\Http\Requests\Origem;

use Illuminate\Foundation\Http\FormRequest;

class InstituicaoEditOrigem extends FormRequest
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
            'descricao' => [
                'required',
                'string'
            ],
            'tipo_id' => [
                'required',
                'integer'
            ],
            'cc_id' => [
                'required',
                'integer'
            ],
            'ativo' => [
                'exclude_without:ativo',
                'required_with:ativo',
                'integer'
            ]
        ];
    }

    public function messages()
    {
        return [
            'descricao.required' => 'A Descrição da Origem é obrigatória',
            'tipo_id.required' => 'A Tipo de Origem é obrigatório',
            'cc_id.required' => 'O Centro de Custo é obrigatório',
        ];
    }
}
