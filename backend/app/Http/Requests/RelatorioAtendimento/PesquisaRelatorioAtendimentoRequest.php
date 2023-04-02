<?php

namespace App\Http\Requests\RelatorioAtendimento;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PesquisaRelatorioAtendimentoRequest extends FormRequest
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
            'data_inicio' => ['required' , 'date'],
            'data_fim' => ['required', 'date'],
            'convenios.*' => ['required', 'exists:convenios,id'],
            'procedimentos.*' => ['required'],
            'profissionais' => ['required'],
            'profissionais.*' => ['required', 'exists:prestadores,id'],
            'status.*' => ['required', Rule::in(['pendente', 'agendado', 'confirmado', 'cancelado', 'finalizado', 'excluir', 'ausente'])],
            'grupos.*' => ['required', 'exists:grupos_procedimentos,id'],
            'setores.*' => ['required', 'exists:setores_exame,id'],
            'solicitantes.*'=>['nullable', 'exists:prestadores_solicitantes,id'],
        ];
    }
}
