<?php

namespace App\Http\Requests\Acomodacoes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Acomodacao;

class InstituicaoCreateAcomodacao extends FormRequest
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
                Rule::in(Acomodacao::getTipos()),
            ],
            'extra_virtual' => [
                'nullable',
                'integer'
            ]
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Campo Obrigatório',
            'tipo_id.in' => 'Tipo de acomodação fora do escopo válido'
        ];
    }
}
