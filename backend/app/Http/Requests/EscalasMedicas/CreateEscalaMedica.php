<?php

namespace App\Http\Requests\EscalasMedicas;

use Illuminate\Foundation\Http\FormRequest;

class CreateEscalaMedica extends FormRequest
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
            'regra' => [
                'required',
                'string',
            ],
            'data' => [
                'required',
                'string',
            ],
            // 'horario_inicio' => [
            //     'required',
            //     'string',
            // ],
            // 'horario_termino' => [
            //     'required',
            //     'string',
            // ],
            'especialidade_id' => [
                'required',
                'integer',
            ],


            'origem_id' => [
                'nullable',
                'integer',
            ],

            'prestadores' => [
                'nullable',
                'array',
            ],

            'prestadores.*.prestador_id' => [
                'required_unless:prestadores,null',
                'integer',
            ],
            'prestadores.*.entrada' => [
                'required_unless:prestadores,null',
                'string',
            ],
            'prestadores.*.saida' => [
                'required_unless:prestadores,null',
                'string',
            ],
            'prestadores.*.observacao' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Campo obrigatório'
        ];
    }
}
