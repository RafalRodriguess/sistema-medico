<?php

namespace App\Http\Requests\ComercialLoja;

use Illuminate\Foundation\Http\FormRequest;

class EditarComercialLojaRequest extends FormRequest
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
            'email' => ['required', 'email'],
            'telefone' => ['required'],
            'rua' => ['required'],
            'numero' => ['required'],
            'cep' => ['required'],
            'bairro' => ['required'],
            'cidade' => ['required'],
            'estado' => ['required'],
            'imagem' => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
            'exibir' => ['nullable'],
            'cartao_credito' => ['nullable'],
            'cartao_entrega' => ['nullable'],
            'dinheiro' => ['nullable'],
            'complemento' => ['nullable'],
            'referencia' => ['nullable'],
        ];
    }
}
