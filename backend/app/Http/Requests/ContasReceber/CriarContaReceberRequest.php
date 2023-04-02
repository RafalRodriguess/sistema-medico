<?php

namespace App\Http\Requests\ContasReceber;

use App\ContaReceber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriarContaReceberRequest extends FormRequest
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
            "tipo" => ['required', Rule::in(ContaReceber::tipos())],
            "forma_pagamento" => ['required', Rule::in(ContaReceber::formas_pagamento())],
            'pessoa_id' => ['nullable'],
            'convenio_id' => ['required_if:tipo,convenio'],
            "valor_parcela" => ['required'],
            "data_vencimento" => ['required', 'date'],
            "data_compensacao" => ['nullable', 'date'],
            "num_parcelas" => ['required', 'numeric'],
            "tipo_parcelamento" => ['nullable', Rule::in(['diario','semanal','quinzenal','mensal','anual'])],
            "descricao" => ['nullable'],
            "num_documento" => ['nullable'],
            "obs" => ['nullable'],
            "conta_id" => ['required', 'exists:contas,id'],
            "plano_conta_id" => ['required', 'exists:planos_contas,id'],
            'parcelas.*.data_vencimento' => ['required_with:tipo_parcelamento'],
            'parcelas.*.valor' => ['required_with:tipo_parcelamento'],
            'titular' => ['required_if:forma_pagamento,cheque'],
            'banco' => ['required_if:forma_pagamento,cheque'],
            'numero_cheque' => ['required_if:forma_pagamento,cheque'],
        ];
    }
}
