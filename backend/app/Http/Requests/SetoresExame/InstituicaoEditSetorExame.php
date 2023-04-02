<?php

namespace App\Http\Requests\SetoresExame;

use Illuminate\Foundation\Http\FormRequest;
use App\SetorExame;
use Illuminate\Validation\Rule;

class InstituicaoEditSetorExame extends FormRequest
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
            'descricao' => ['required', 'string'],
            'tipo' => ['required', 'string', Rule::in(SetorExame::tipos)],
            'ativo' => ['required', Rule::in([0,1])],
        ];
    }
}
