<?php

namespace App\Http\Requests\CentroCirurgico;

use Illuminate\Foundation\Http\FormRequest;

class EditCentroCirurgico extends FormRequest
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
            'cc_id' => [
                'required',
                'integer'
            ], 
            
            // Horário de Funcionamento do Centro Cirúrgico
            'segunda_feira_inicio' => [
                'nullable',
                'required_with:segunda_feira_fim',
                'string'
            ],
            'segunda_feira_fim' => [
                'nullable',
                'required_with:segunda_feira_inicio',
                'string'
            ],
            'terca_feira_inicio' => [
                'nullable',
                'required_with:terca_feira_fim',
                'string'
            ],
            'terca_feira_fim' => [
                'nullable',
                'required_with:terca_feira_inicio',
                'string'
            ],
            'quarta_feira_inicio' => [
                'nullable',
                'required_with:quarta_feira_fim',
                'string'
            ],
            'quarta_feira_fim' => [
                'nullable',
                'required_with:quarta_feira_inicio',
                'string'
            ],
            'quinta_feira_inicio' => [
                'nullable',
                'required_with:quinta_feira_fim',
                'string'
            ],
            'quinta_feira_fim' => [
                'nullable',
                'required_with:quinta_feira_inicio',
                'string'
            ],
            'sexta_feira_inicio' => [
                'nullable',
                'required_with:sexta_feira_fim',
                'string'
            ],
            'sexta_feira_fim' => [
                'nullable',
                'required_with:sexta_feira_inicio',
                'string'
            ],
            'sabado_inicio' => [
                'nullable',
                'required_with:sabado_fim',
                'string'
            ],
            'sabado_fim' => [
                'nullable',
                'required_with:sabado_inicio',
                'string'
            ],
            'domingo_inicio' => [
                'nullable',
                'required_with:domingo_fim',
                'string'
            ],
            'domingo_fim' => [
                'nullable',
                'required_with:domingo_inicio',
                'string'
            ],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Campo Obrigatório',
            'required_with' => 'Campo Obrigatório'
        ];
    }
}
