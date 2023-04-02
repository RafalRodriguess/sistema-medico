<?php

namespace App\Http\Requests\Setores;

use Illuminate\Foundation\Http\FormRequest;

class InstituicaoCreateSetor extends FormRequest
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
                'string',
            ],
            'descricao' => [
                'nullable',
                'string'
            ]
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O Nome do Setor é obtigatório',
            'nome.string' => 'O Nome do Setor deve ser um texto',

            'descricao.string' => 'A descrição do setor deve ser um texto',
        ];
    }
}
