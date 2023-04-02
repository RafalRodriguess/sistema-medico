<?php

namespace App\Http\Requests\Pessoa;

use App\Pessoa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IniciarCriacaoPessoaRequest extends FormRequest
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
            'nome' => [
                'nullable'
            ],
            'cpf' => [
                'nullable'
            ],
            'nome_mae' => [
                'nullable'
            ],
            'personalidade' => [
                'nullable',
                Rule::in(Pessoa::getPersonalidades())
            ],
            'tipo' => [
                'nullable',
                Rule::in(Pessoa::getTipos())
            ]
        ];
    }
}
