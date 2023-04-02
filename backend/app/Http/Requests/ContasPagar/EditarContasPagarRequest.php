<?php

namespace App\Http\Requests\ContasPagar;

use App\ContaPagar;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditarContasPagarRequest extends FormRequest
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
            'pessoa_id' => ['required_if:tipo,paciente', 'required_if:tipo,fornecedor'],
            'prestador_id' => ['required_if:tipo,prestador'],
            "valor_parcela" => ['required'],
            "total" => ['required'],
            "data_vencimento" => ['required'],
            "data_compensacao" => ['required_if:forma_pagamento,cheque'],
            "descricao" => ['required'],
            "numero_doc" => ['nullable'],
            "obs" => ['nullable'],
            "conta_id" => ['nullable', 'exists:contas,id'],
            "plano_conta_id" => ['nullable',  'exists:planos_contas,id'],
            'titular' => ['required_if:forma_pagamento,cheque'],
            'banco' => ['required_if:forma_pagamento,cheque', 'required_if:forma_pagamento,transferencia_bancaria'],
            'numero_cheque' => ['required_if:forma_pagamento,cheque'],
            'agencia' => ['required_if:forma_pagamento,transferencia_bancaria'],
            'conta' => ['required_if:forma_pagamento,transferencia_bancaria'],
            "data_emissao_nf" => ['nullable'],
            "chave_pix" => ['nullable'],
            'cc.*.centro_custo_id' => ['nullable', 'exists:centros_de_custos,id'],
            'cc.*.valor' => ['nullable', 'required_with:cc.*.centro_custo_id'],
            'cartao_credito_id' => ['nullable', 'required_if:forma_pagamento,cartao_credito', 'exists:cartoes_credito,id'],
        ];
    }
}
