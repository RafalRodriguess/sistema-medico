<?php

namespace App\Http\Requests\Fornecedor;

use App\Pessoa;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EditFornecedorRequest extends FormRequest
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
            'personalidade' => [
                'required',
                Rule::in(Pessoa::getPersonalidades()),
            ],
            // Pessoa Física
            'nome' => [
                'required_if:personalidade,1',
                'nullable',
                'string'
            ],
            'cpf' => [
                'required_if:personalidade,1',
                'nullable',
                'string'
            ],
            'telefone1' => [
                'required',
                'string'
            ],
            'telefone2' => [
                'nullable',
                'string'
            ],
            'telefone3' => [
                'nullable',
                'string'
            ],
            'email' => [
                'nullable',
                'string'
            ],
            'cep' => [
                'nullable',
                'string'
            ],
            'estado' => [
                'nullable',
                'string'
            ],
            'cidade' => [
                'nullable',
                'string'
            ],
            'bairro' => [
                'nullable',
                'string'
            ],
            'rua' => [
                'nullable',
                'string'
            ],
            'numero' => [
                'nullable',
                'integer'
            ],
            'complemento' => [
                'nullable',
                'string'
            ],
            // Pessoa Juridica
            'nome_fantasia' => [
                'nullable',
                'required_if:personalidade,2',
                'string'
            ],
            'cnpj' => [
                'nullable',
                'string'
            ],
            'razao_social' => [
                'nullable',
                'required_if:personalidade,2',
                'string'
            ],
            'site' => [
                'nullable',
                'string'
            ],
            'banco' => [
                'nullable',
                'string'
            ],
            'agencia' => [
                'nullable',
                'string'
            ],
            'conta_corrente' => [
                'nullable',
                'string'
            ],
            
            // Contato de referência
            'referencia_relacao' => [
                'nullable',
                'string'
            ],
            'referencia_nome' => [
                'nullable',
                'string'
            ],
            'referencia_telefone' => [
                'nullable',
                'string'
            ],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Campo obrigatório',
            'required_with' => 'Campo obrigatório',
            'required_if' => 'Campo obrigatório',
        ];
    }
}
