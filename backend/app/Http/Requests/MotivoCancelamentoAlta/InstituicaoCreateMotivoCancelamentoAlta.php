<?php

namespace App\Http\Requests\MotivoCancelamentoAlta;

use Illuminate\Foundation\Http\FormRequest;

class InstituicaoCreateMotivoCancelamentoAlta extends FormRequest
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
            'descricao_motivo_cancelamento_alta' => [
                'required',
                'string'
            ], 
            'tipo' => [
                'required',
                'integer'
            ], 
            'ativo' => [
                'integer'
            ]
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Campo obrigatório',
        ];
    }
}
