<?php

namespace App\Http\Requests\Agendamentos;

use Illuminate\Foundation\Http\FormRequest;

class PagamentoAgendamentoRequest extends FormRequest
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
            'desconto' => ['nullable'],
            'carteirinha_id_pagamento' => ['nullable', 'exists:pessoas_carteiras_planos_convenio,id'],
            'pagamento' => ['required'],
            'pagamento.*.conta_id' => ['required'],
            'pagamento.*.plano_conta_id' => ['required'],
            'pagamento.*.valor' => ['required'],
            'pagamento.*.data' => ['required'],
            'pagamento.*.forma_pagamento' => ['required'],
            'pagamento.*.recebido' => ['nullable'],
            'pagamento.*.num_parcelas' => ['required', 'gt:0'],
            'pagamento.*.maquina_id' => ['nullable', 'exists:maquinas_cartoes,id'],
            'pagamento.*.taxa' => ['nullable'],
            'pagamento.*.cod_aut' => ['nullable'],
        ];
    }
}
