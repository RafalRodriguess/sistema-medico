<?php

namespace App\Http\Requests\InstituicaoBackend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Instituicao;

class EditarInstituicaoBackendResquest extends FormRequest
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
            'nome' => ['required'],
            'razao_social' => ['required'],
            'cnes' => ['required', 'numeric', 'digits:7'],
            'imagem' => ['nullable'],
            'email'=> ['required', 'email'],
            'telefone'=> ['required'],
            'rua'=> ['required'],
            'numero'=> ['required'],
            'cep'=> ['required'],
            'cnpj'=> ['required'],
            'bairro'=> ['required'],
            'cidade'=> ['required'],
            'estado'=> ['required'],
            'complemento' => ['string', 'nullable'],
            'inscricao_municipal' => ['required'],
            'inscricao_estadual' => ['nullable'],
            'cartao_entrega' => ['nullable', 'boolean'],
            'dinheiro' => ['nullable', 'boolean'],
            'realiza_entrega' => ['nullable', 'boolean'],
            'retirada_loja' => ['nullable', 'boolean'],
            'ramo' => ['required', Rule::in(Instituicao::getRamos())],
            'tipo' => ['required', Rule::in(Instituicao::getTipos())],
            'finalizar_consultorio' => ['nullable'],
        ];
    }
}
