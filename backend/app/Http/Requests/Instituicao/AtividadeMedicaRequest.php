<?php

namespace App\Http\Requests\Instituicao;

use Illuminate\Foundation\Http\FormRequest;

class AtividadeMedicaRequest extends FormRequest
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
            'descricao' => 'required|string',
            'ordem_apresentacao' => 'required',
            'tipo_funcao' => 'required|in:Cirurgião,Auxiliar,Anestesista,Outros',
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
            'descricao.required' => 'O campo descrição é obrigatório.',
            'ordem_apresentacao.required' => 'O campo ordem é obrigatório.',
            'tipo_funcao.required' => 'O campo tipo de função é obrigatório.',
        ];
    }
}
