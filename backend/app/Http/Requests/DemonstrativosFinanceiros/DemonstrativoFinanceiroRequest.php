<?php

namespace App\Http\Requests\DemonstrativosFinanceiros;

use App\ContaPagar;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DemonstrativoFinanceiroRequest extends FormRequest
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
            'data_inicio' => ['required'],
            'data_fim' => ['required'],
            'tipo_pesquisa' => ['required', Rule::in(['data_vencimento', 'data_pago', 'data_compensacao', 'created_at', 'bancaria'])],
            'conta_id' => ['nullable', 'exists:contas,id'],
            'formaPagamento' => ['nullable', Rule::in(ContaPagar::formas_pagamento())],
            'menor' => ['nullable'],
            'maior' => ['nullable'],
            'status_id' => ['nullable'],
            'forma_recebimento' => ['nullable', 'exists:formas_recebimento,id'],
            'plano_conta_caixa_id' => ['nullable', 'exists:planos_conta_caixa,id'],
            'tipo_relatorio' => ['required'],
            'natureza' => ['required'],
        ];
    }
}
