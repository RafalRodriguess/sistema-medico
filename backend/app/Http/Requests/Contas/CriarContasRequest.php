<?php

namespace App\Http\Requests\Contas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Conta;

class CriarContasRequest extends FormRequest
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
            'descricao' => ['required'],
            'tipo' => ['required', Rule::in(array_keys(Conta::opcoes_tipo))],
            'banco' => ['required_unless:tipo,1'],
            'agencia' => ['required_unless:tipo,1'],
            'conta' => ['required_unless:tipo,1'],
            'situacao' => ['required'],
            'saldo_inicial' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'required_unless'=>'Este campo é obrigatório a menos que tipo seja igual a Caixa Fisico',
        ];
    }
}
