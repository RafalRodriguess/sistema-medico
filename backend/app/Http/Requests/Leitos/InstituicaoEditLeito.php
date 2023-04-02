<?php

namespace App\Http\Requests\Leitos;

use Illuminate\Foundation\Http\FormRequest;

class InstituicaoEditLeito extends FormRequest
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
            'tipo' => [
                'required',
                'integer'
            ],
            'situacao' => [
                'required',
                'integer'
            ],
            'quantidade' => [
                'required',
                'integer'
            ],
            'sala' => [
                'required',
                'string'
            ],
            'caracteristicas' => [
                'nullable',
                'array'
            ],
            'acomodacao_id' => [
                'nullable',
                'integer'
            ],
            'especialidade_id' => [
                'nullable',
                'integer'
            ],
            'medico_id' => [
                'nullable',
                'integer'
            ],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Campo Obrigatório',
        ];
    }
}
