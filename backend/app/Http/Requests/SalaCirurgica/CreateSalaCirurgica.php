<?php

namespace App\Http\Requests\SalaCirurgica;

use Illuminate\Foundation\Http\FormRequest;

class CreateSalaCirurgica extends FormRequest
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
            // Salas Cirúrgicas pertencentes ao Centro Cirúrgico 
            'salas_cirurgicas' => [
                'nullable',
                'array'
            ],
            'salas_cirurgicas.*.descricao' => [
                'nullable',
                'required_with:salas_cirurgicas',
                'string'
            ],
            'salas_cirurgicas.*.sigla' => [
                'nullable',
                'required_with:salas_cirurgicas',
                'string'
            ],
            'salas_cirurgicas.*.tempo_minimo_preparo' => [
                'nullable',
                'string'
            ],
            'salas_cirurgicas.*.tempo_minimo_utilizacao' => [
                'nullable',
                'string'
            ],
            'salas_cirurgicas.*.tipo' => [
                'nullable',
                'required_with:salas_cirurgicas',
                'integer'
            ],
        ];
    }

    public function messages()
    {
        return [
            'required_with' => 'Campo Obrigatório'
        ];
    }
}
