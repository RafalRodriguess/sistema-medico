<?php

namespace App\Http\Requests\UnidadeInternacao;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class InstituicaoEditUnidadeInternacao extends FormRequest
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
