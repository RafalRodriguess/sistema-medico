<?php

namespace App\Http\Requests\RelatorioAuditoria;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PesquisarRelatorioAuditoriaRequest extends FormRequest
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
            'tipo' => ['required', 'in:data_auditoria,data_agendamento'],
            'data_inicio' => ['required'],
            'data_fim' => ['required'],
            'status.*' => ['required', Rule::in(['pendente', 'agendado', 'confirmado', 'cancelado', 'finalizado', 'excluir', 'ausente', 'em_atendimento', 'finalizado_medico'])],
            'usuarios.*' => ['nullable', 'exists:instituicao_usuarios,id'],
        ];
    }
}
