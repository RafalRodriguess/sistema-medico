<?php

namespace App\Http\Requests\Pessoa;

use App\Pessoa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditPessoaRequest extends FormRequest
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
            'tipo' => [
                'required',
                Rule::in(Pessoa::getTipos()),
            ],
            // Pessoa Física
            'nome' => [
                'required_if:personalidade,1',
                'string',
                'nullable'
            ],
            'cpf' => [
                'nullable',
                'string',
                'nullable'
            ],
            'telefone1' => [
                'required',
                'string'
            ],
            'telefone2' => [
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
            'nome_pai' => [
                'nullable',
                'string'
            ],

            'nome_mae' => [
                'nullable',
                'string'
            ],

            'identidade' => [
                'nullable',
                'string',
            ],

            'orgao_expedidor' => [
                'nullable',
                'string',
            ],
            
            'data_emissao' => [
                'nullable',
            ],

            'sexo' => [
                'nullable',
                Rule::in(Pessoa::getSexos())
            ],

            'estado_civil' => [
                'nullable',
                'string',
                Rule::in(Pessoa::getEstadosCivil())
            ],
            
            'nascimento' => [
                'required_if:personalidade,1',
            ],
            // Pessoa Juridica
            'nome_fantasia' => [
                'nullable',
                'required_if:personalidade,2',
                'string'
            ],
            'cnpj' => [
                'nullable',
                'required_if:personalidade,2',
                'string'
            ],
            'razao_social' => [
                'nullable',
                'required_if:personalidade,2',
                'string'
            ],
            'site' => [
                'nullable',
                'required_if:personalidade,2',
                'string'
            ],
            'banco' => [
                'nullable',
                'required_if:personalidade,2',
                'string'
            ],
            'agencia' => [
                'nullable',
                'required_if:personalidade,2',
                'string'
            ],
            'conta_corrente' => [
                'nullable',
                'required_if:personalidade,2',
                'string'
            ],
            // Contato de referência
            'referencia_relacao' => [
                'nullable',
                'string',
                'required_if:gerar_via_acompanhante,1'
            ],
            'referencia_nome' => [
                'nullable',
                'string',
                'required_if:gerar_via_acompanhante,1'
            ],
            'referencia_telefone' => [
                'nullable',
                'string',
                'required_if:gerar_via_acompanhante,1'
            ],
            'obs' => [
                'nullable',
                'string'
            ],
            
            'telefone3' => [
                'nullable',
                'string'
            ],
            'naturalidade' => [
                'nullable',
                'string'
            ],
            'indicacao_descricao' => [
                'nullable',
                'string'
            ],
            'profissao' => [
                'nullable',
                'string'
            ],
            'referencia_documento' => [
                'nullable',
                'string',
                'required_if:gerar_via_acompanhante,1'
            ],
            'carteirinha.*.convenio_id' => ['nullable', 'exists:convenios,id'],
            'carteirinha.*.plano_id' => ['nullable', 'exists:convenios_planos,id'],
            'carteirinha.*.carteirinha' => ['nullable'],
            'carteirinha.*.validade' => ['nullable'],
            'carteirinha.*.tipo' => ['nullable'],
            'carteirinha.*.id' => ['nullable'],
            'gerar_via_acompanhante' => ['nullable'],
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
