<?php

namespace App\Http\Requests\ProcedimentoAtendimento;

use Illuminate\Foundation\Http\FormRequest;

class CriarProcedimentoAtendimentoRequest extends FormRequest
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
            'convenio_id' => ['required', 'exists:convenios,id'],
            'plano_id' => ['required', 'exists:convenios_planos,id'],
            'tipo_atendimento' => ['required', 'in:urgencia,ambulatorio,internacao'],
            'origem_id' => ['nullable', 'exists:origens,id'],
            'unidade_internacao_id' => ['nullable', 'exists:unidades_internacoes,id'],
            'procedimento_id' => ['required', 'exists:procedimentos,id'],
            'proc.*.grupo_faturamento_id' => ['required', 'exists:grupos_faturamento,id'],
            'proc.*.procedimento_cod' => ['nullable', 'gt:0'],
            'proc.*.procedimento_id' => ['required', 'exists:procedimentos,id'],
            'proc.*.quantidade' => ['required', 'gt:0'],
        ];
    }
}
