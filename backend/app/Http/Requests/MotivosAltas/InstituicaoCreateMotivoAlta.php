<?php

namespace App\Http\Requests\MotivosAltas;

use Illuminate\Foundation\Http\FormRequest;

class InstituicaoCreateMotivoAlta extends FormRequest
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
            'descricao_motivo_alta' => [
                'required',
                'string'
            ], 
            'tipo' => [
                'required',
                'integer'
            ], 
            'codigo_alta_sus' => [
                'required',
                'integer'
            ],
            'motivo_transferencia_id' => [
                'nullable',
                'required_if:tipo,4',
                'integer'
            ],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Campo obrigatório',
            'required_if' => 'Campo obrigatório para tipo Transferência'
        ];
    }
}
