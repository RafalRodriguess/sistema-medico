<?php

namespace App\Http\Requests\Leitos;

use Illuminate\Foundation\Http\FormRequest;

class InstituicaoCreateLeito extends FormRequest
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
            'leitos' => [
                'required',
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
            'leitos.*.quantidade' => [
                'nullable',
                'required_with:leitos',
                'integer'
            ],
            'leitos.*.sala' => [
                'nullable',
                'required_with:leitos',
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
        ];
    }

    public function messages()
    {
        return [
            'leitos.required' => 'Nenhum leito foi registrado',
            'required_with' => 'Campo Obrigatório',
        ];
    }
}
