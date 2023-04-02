<?php

namespace App\Http\Requests\InstituicaoTransferencia;

use Illuminate\Foundation\Http\FormRequest;

class InstituicaoTransferenciaRequest extends FormRequest
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
            'cnes' => [
                'nullable',
                'numeric',
                'digits:7'
            ],
            'cep' => [
                'required',
                'string'
            ],
            'estado' => [
                'required',
                'string'
            ],
            'cidade' => [
                'required',
                'string'
            ],
            'bairro' => [
                'required',
                'string'
            ],
            'rua' => [
                'required',
                'string'
            ],
            'numero' => [
                'required',
                'integer'
            ],
            'complemento' => [
                'nullable',
                'string'
            ],
            'telefone' => [
                'nullable',
                'string',
                'min:9'
            ],
            'email' => [
                'nullable',
                'email'
            ]
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Campo obrigatório',
            'email' => 'Insira um e-mail válido'
        ];
    }
}
