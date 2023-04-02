<?php

namespace App\Http\Requests\CentrosCustos;

use Illuminate\Foundation\Http\FormRequest;

class InstituicaoEditCentroCusto extends FormRequest
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
            'pai_id' => [
                'nullable',
                'integer',
            ],
            'grupo_id' => [
                'nullable',
                'integer'
            ],
            'codigo' => [
                'required',
                'string'
            ],
            'descricao' => [
                'required',
                'string'
            ],
            'lancamento' => [
                'nullable',
                'integer'
            ],
            'ativo' => [
                'nullable',
                'integer'
            ],
            'email' => [
                'nullable',
                'string'
            ],
            'gestor' => [
                'nullable',
                'string'
            ],
            'setor_exame_id' => [
                'numeric',
                'nullable'
            ]
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Campo Obrigatório',
        ];
    }
}
