<?php

namespace App\Http\Requests\RelatorioConclusoes;

use Illuminate\Foundation\Http\FormRequest;

class TabelaRelatorioConclusoesRequest extends FormRequest
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
            'data_inicio' => ['required', 'date', 'before_or_equal:data_fim'],
            'data_fim' => ['required', 'after_or_equal:data_inicio', 'date'],
            'motivo_conclusao_id' => ['required', 'exists:motivos_conclusoes,id'],
            'usuario_id' => ['required', 'exists:instituicao_usuarios,id'],
            'paciente_id' => ['nullable', 'exists:pessoas,id']
        ];
    }

    public function messages()
    {
        return [
            'usuario_id.required' => 'O campo profissionais é obrigatório',
            'usuario_id.exists' => 'Selecione um valor válido para profissionais',
            'paciente_id.required' => 'O campo paciente é obrigatório',
            'paciente_id.exists' => 'Selecione um valor válido para paciente',
            'motivo_conclusao_id.required' => 'O campo motivo é obrigatório',
            'motivo_conclusao_id.exists' => 'Selecione um valor válido para motivo',
        ];
    }
}
