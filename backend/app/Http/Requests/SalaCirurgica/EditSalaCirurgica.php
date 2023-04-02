<?php

namespace App\Http\Requests\SalaCirurgica;

use Illuminate\Foundation\Http\FormRequest;

class EditSalaCirurgica extends FormRequest
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
            'sigla' => [
                'required',
                'string'
            ],
            'tempo_minimo_preparo' => [
                'nullable',
                'string'
            ],
            'tempo_minimo_utilizacao' => [
                'nullable',
                'string'
            ],
            'tipo' => [
                'required',
                'integer'
            ],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Campo Obrigatório'
        ];
    }
}
