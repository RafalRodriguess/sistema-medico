<?php

namespace App\Http\Requests\ContasPagar;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\ContaPagar;

class PagarParcelaContasRequest extends FormRequest
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
            'valor_pago' => ['required'],
            'data_pago' => ['required', 'date'],
            'data_compensacao' => ['nullable', 'date'],
            'obs' => ['nullable'],
            "forma_pagamento" => ['required', Rule::in(ContaPagar::formas_pagamento())],
            "conta_id" => ['required', 'exists:contas,id'],
            "plano_conta_id" => ['nullable', 'exists:planos_contas,id'],
            'forma_pagamento_id' => ['nullable', 'exists:formas_pagamento,id'],
            'titular' => ['nullable'],
            'banco' => ['nullable'],
            'numero_cheque' => ['nullable'],
            'agencia' => ['nullable'],
            'chave_pix' => ['nullable'],
            'conta' => ['nullable'],
            'desc_juros_multa' => ['nullable'],
            'pagar_menor' => ['nullable'],
            'data_vencimento' => ['nullable', 'required_without:pagar_menor', 'date'],
            // 'centro_custo_id' => ['nullable', 'exists:centro_custos,id'],
        ];
    }
}
