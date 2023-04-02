<?php

namespace App\Http\Requests\Atendimentos;

use Illuminate\Foundation\Http\FormRequest;

class AdminEditAtendimento extends FormRequest
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
            'nome.required' => 'O Nome do Atendimento é obtigatório',
            'nome.string' => 'O Nome do Atendimento deve ser um texto',

            'descricao.string' => 'A descrição do atendimento deve ser um texto',
        ];
    }
}
