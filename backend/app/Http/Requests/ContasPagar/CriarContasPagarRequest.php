<?php

namespace App\Http\Requests\ContasPagar;

use App\ContaPagar;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriarContasPagarRequest extends FormRequest
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
            "tipo" => ['required', Rule::in(ContaPagar::tipos())],
            "forma_pagamento" => ['nullable', Rule::in(ContaPagar::formas_pagamento())],
            'pessoa_id' => ['required_if:tipo,paciente'],
            'prestador_id' => ['required_if:tipo,prestador'],
            'fornecedor_id' => ['required_if:tipo,fornecedor'],
            "total" => ['required_if:cotacao,0'],
            "data_vencimento" => ['required_if:cotacao,0', 'date'],
            "data_compensacao" => ['required_if:forma_pagamento,cheque', 'date'],
            "num_parcelas" => ['required_if:cotacao,0', 'numeric'],
            "tipo_parcelamento" => ['nullable', Rule::in(['diario','semanal','quinzenal','mensal','anual'])],
            "descricao" => ['required'],
            "numero_doc" => ['nullable'],
            "obs" => ['nullable'],
            "conta_id" => ['nullable', $this['cotacao'] === 0 ? 'exists:contas,id' : ''],
            "plano_conta_id" => ['nullable',  $this['cotacao'] === 0 ? 'exists:planos_contas,id' : ''],
            'parcelas.*.data_vencimento' => ['required_with:tipo_parcelamento'],
            'parcelas.*.valor' => ['required_with:tipo_parcelamento'],
            'titular' => ['required_if:forma_pagamento,cheque'],
            'banco' => ['required_if:forma_pagamento,cheque', 'required_if:forma_pagamento,transferencia_bancaria'],
            'numero_cheque' => ['required_if:forma_pagamento,cheque'],
            'tipo_divisao' => ['required_with:tipo_parcelamento'],
            'agencia' => ['required_if:forma_pagamento,transferencia_bancaria'],
            'conta' => ['required_if:forma_pagamento,transferencia_bancaria'],
            "data_emissao_nf" => ['nullable'],
            "chave_pix" => ['nullable'],
            'cc.*.centro_custo_id' => ['nullable', 'exists:centros_de_custos,id'],
            'cc.*.valor' => ['nullable', 'required_with:cc.*.centro_custo_id'],
            'cartao_credito_id' => ['nullable', 'required_if:forma_pagamento,cartao_credito', 'exists:cartoes_credito,id'],
            'data_compra_cartao' => ['nullable', 'required_if:forma_pagamento,cartao_credito'],
            'status' => ['nullable'],
            'valor_pago' => ['nullable', 'required_if:status,1'],
            'desc_juros_multa' => ['nullable', 'required_if:status,1'],
            'data_pago' => ['nullable', 'required_if:status,1'],
            'duplicar' => ['nullable'],
        ];
    }

    public function messages()
    {
        return [
            'required_with' => 'O campo é obrigatório'
        ];
    }
}
