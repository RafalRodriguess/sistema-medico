<?php

namespace App\Http\Requests\EquipeCirurgica;

use Illuminate\Foundation\Http\FormRequest;

class EquipeCirurgicaRequest extends FormRequest
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
            'prestadores' => [
                'required',
                'array'
            ],
            'prestadores.*.tipo' => [
                'required',
                'integer'
            ],
            'prestadores.*.prestador_id' => [
                'required',
                'integer'
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
