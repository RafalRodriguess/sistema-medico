<?php

namespace App\Http\Requests\Agendamentos;

use Illuminate\Foundation\Http\FormRequest;

class SalvarAgendamentoProcedimentoUpdateRequest extends FormRequest
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
            'agendamentos.*.agendamento_id' => ['required', 'exists:agendamentos,id'],
            'agendamento.*.*.agendamento_procedimento_id' => ['required', 'exists:agendamentos_procedimentos,id'],
            'agendamento.*.*.convenio_id' => ['required', 'exists:convenios,id'],
            'agendamento.*.*.procedimento_id' => ['required', 'exists:procedimentos_instituicoes,id'],
            'agendamento.*.*.valor_atual' => ['required'],
            'agendamento.*.*.valor_repasse' => ['required'],
            'agendamento.*.*.valor_convenio' => ['required'],
        ];
    }
}
