<?php

namespace App\Http\Requests\Odontologico;

use Illuminate\Foundation\Http\FormRequest;

class AlterarNegociadorResponsavelRequest extends FormRequest
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
            'negociador_id_item' => ['nullable', 'exists:instituicao_usuarios,id'],
            'responsavel_id_item' => ['nullable', 'exists:instituicao_usuarios,id'],
            'itens_alteracoes' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'itens_alteracoes.required' => 'Selecione um procedimento pelo menos!',
        ];
    }
}
