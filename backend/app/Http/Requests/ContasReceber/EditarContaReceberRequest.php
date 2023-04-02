<?php

namespace App\Http\Requests\ContasReceber;

use App\ContaReceber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditarContaReceberRequest extends FormRequest
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
            "data_vencimento" => ['required'],
            "data_compensacao" => ['nullable'],
            "descricao" => ['nullable'],
            "num_documento" => ['nullable'],
            "obs" => ['nullable'],
            "conta_id" => ['required', 'exists:contas,id'],
            "plano_conta_id" => ['required', 'exists:planos_contas,id'],
            'titular' => ['required_if:forma_pagamento,cheque'],
            'banco' => ['required_if:forma_pagamento,cheque'],
            'numero_cheque' => ['required_if:forma_pagamento,cheque'],
        ];
    }
}
