<?php

namespace App\Http\Requests\Odontologico;

use Illuminate\Foundation\Http\FormRequest;

class FinalizarOrcamentoFinanceiroRequest extends FormRequest
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
            'itens_aprovados' => ['required'],
            'negociador_id' => ['required', 'exists:instituicao_usuarios,id'],
            'responsavel_id' => ['required', 'exists:instituicao_usuarios,id'],
            'desconto' => ['nullable'],
            'total_a_pagar_pagamento' => ['nullable'],
            'pagamento' => ['required_with:total_a_pagar_pagamento'],
            'pagamento.*.conta_id' => ['required'],
            'pagamento.*.plano_conta_id' => ['required'],
            'pagamento.*.valor' => ['required'],
            'pagamento.*.data' => ['required'],
            'pagamento.*.forma_pagamento' => ['required'],
            'pagamento.*.recebido' => ['nullable'],
            'pagamento.*.num_parcelas' => ['required', 'gt:0'],
        ];
    }
}
