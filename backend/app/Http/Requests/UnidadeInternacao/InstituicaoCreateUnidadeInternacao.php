<?php

namespace App\Http\Requests\UnidadeInternacao;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InstituicaoCreateUnidadeInternacao extends FormRequest
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
            'nome' => [
                'required',
                'string'
            ],
            'cc_id' => [
                'required',
                'integer'
            ],
            'hospital_dia' => [
                'required',
                'integer',
                Rule::in('0', '1')
            ],
            'tipo_unidade' => [
                'required',
                'integer'
            ],
            'localizacao' => [
                'required',
                'string'
            ],
            'ativo' => [
                'nullable',
                'exclude_without:ativo',
                'integer'
            ],

            'leitos' => [
                'nullable',
                'exclude_without:leitos',
                'array'
            ],
            'leitos.*.descricao' => [
                'nullable',
                'required_with:leitos',
                'string'
            ],
            'leitos.*.tipo' => [
                'nullable',
                'required_with:leitos',
                'integer'
            ],
            'leitos.*.situacao' => [
                'nullable',
                'required_with:leitos',
                'integer'
            ],
            'leitos.*.sala' => [
                'nullable',
                'string'
            ],
            'leitos.*.caracteristicas' => [
                'nullable',
                'array'
            ],
            'leitos.*.acomodacao_id' => [
                'nullable',
                'integer'
            ],
            'leitos.*.especialidade_id' => [
                'nullable',
                'integer'
            ],
            'leitos.*.medico_id' => [
                'nullable',
                'integer'
            ],
            'leitos.*.leito_virtual' => [
                'nullable',
                'in:on'
            ]
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Campo Obrigatório',
            'required_with' => 'Campo Obrigatório',
        ];
    }
}
