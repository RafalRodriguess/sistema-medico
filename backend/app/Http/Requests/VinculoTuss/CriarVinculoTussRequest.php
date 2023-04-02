<?php

namespace App\Http\Requests\VinculoTuss;

use Illuminate\Foundation\Http\FormRequest;

class CriarVinculoTussRequest extends FormRequest
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
            'tuss.*.terminologia_id' => ['required'],
            'tuss.*.cod_termo' => ['required'],
            'tuss.*.termo' => ['required'],
            'tuss.*.descricao_detalhada' => ['nullable'],
            'tuss.*.data_vigencia' => ['required'],
            'tuss.*.data_vigencia_fim' => ['nullable'],
            'tuss.*.data_implantacao_fim' => ['required'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'O campo é obrigatório.',
        ];
    }
}
