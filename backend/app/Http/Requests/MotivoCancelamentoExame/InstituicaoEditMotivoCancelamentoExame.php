<?php

namespace App\Http\Requests\MotivoCancelamentoExame;

use App\MotivoCancelamentoExame;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InstituicaoEditMotivoCancelamentoExame extends FormRequest
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
                'max:190'
            ],
            'tipo' => [
                'required',
                Rule::in(MotivoCancelamentoExame::tipos)
            ],
            'ativo' => [
                'required',
                Rule::in([0,1])
            ]
        ];
    }
}
