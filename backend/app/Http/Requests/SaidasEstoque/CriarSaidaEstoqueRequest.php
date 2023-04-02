<?php

namespace App\Http\Requests\SaidasEstoque;

use App\Instituicao;
use App\SaidaEstoque;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriarSaidaEstoqueRequest extends FormRequest
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
        $request = request();
        $instituicao = Instituicao::find(request()->session()->get("instituicao"));
        return [
            'observacoes' => [
                'string',
                'nullable'
            ],
            'estoques_id' => [
                'numeric',
                'required', Rule::exists('estoques', 'id')->where('instituicao_id', $instituicao->id)
            ],
            'centros_custos_id' => [
                'nullable',
                Rule::exists('centros_de_custos', 'id')->where('instituicao_id', $instituicao->id)
            ],
            'gerar_conta' => [
                'nullable',
                'in:on'
            ],

            'produtos' => [
                'array',
                'required'
            ],
            'produtos.*.id_entrada_produto' => [
                'numeric',
                'required',
                Rule::exists('estoque_entradas_produtos', 'id')
            ],
            'produtos.*.codigo_de_barras' => [
                'string',
                'nullable'
            ],
            'produtos.*.quantidade' => [
                'numeric',
                'min:0', 'required'
            ],
            
            'conta_id' => [
                'nullable',
                'required_with:gerar_conta'
            ],
            'plano_conta_id' => [
                'nullable',
                'required_with:gerar_conta'
            ],
            'tipo_destino' => [
                'nullable',
                'required_with:gerar_conta', Rule::in(array_keys(SaidaEstoque::destino_saida))
            ],
            'pessoa_id' => [
                'nullable', Rule::requiredIf(function () use ($request) {
                    return $request->get('gerar_conta', false) && $request->get('tipo_destino', -1) == 1;
                })
            ],
            'agendamento_id' => [
                'nullable', Rule::requiredIf(function () use ($request) {
                    return $request->get('gerar_conta', false) && $request->get('tipo_destino', -1) == 2;
                })
            ],

            'pagamentos' => [
                'nullable',
                'required_with:gerar_conta'
            ],
            'pagamentos.*.valor' => [
                'required'
            ],
            'pagamentos.*.data' => [
                'required'
            ],
            'pagamentos.*.forma_pagamento' => [
                'required'
            ],
            'pagamentos.*.recebido' => [
                'nullable'
            ],
        ];
    }

    function messages()
    {
        return [
            'produtos.required' => 'Escolha ao menos um produto',
            'produtos.*.quantidade.*' => 'Quantidade inválida',
            'tipo_destino.required_with' => 'Escolha um destino para gerar a conta',

            // 'required' => 'Campo necessário',
            // 'exists' => 'Valor escolhido é inválido',
            // 'estoques_id.*' => 'Selecione um estoque válido',
        ];
    }
}
