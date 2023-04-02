<?php

namespace App\Http\Requests\Agendamentos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use function Clue\StreamFilter\fun;

class SalvarAgendamentoBackendRequest extends FormRequest
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
            'inst_prest_id' => ['required', 'exists:instituicoes_agenda,id'],
            'data_agenda' => ['required'],
            'hora_agenda' => ['required'],
            'hora_agenda_final' => ['required'],
            'paciente_agenda' => ['required'],
            'telefone_paciente_agenda' => ['nullable', 'required_if:teleatendimento,1'],
            'data_paciente_agenda' => ['nullable', 'required_if:teleatendimento,1'],
            'total_procedimentos_agendar' => ['required'],
            'convenio' => ['required'],
            'convenio.*.convenio_agenda' => ['required'],
            'convenio.*.procedimento_agenda' => ['required'],
            'convenio.*.valor' => ['required'],
            'convenio.*.qtd_procedimento' => ['required'],
            'convenio.*.desconto' => ['nullable'],
            'acompanhante' => ['nullable'],
            'acompanhante_relacao' => ['nullable', 'required_if:acompanhante,1'],
            'acompanhante_nome' => ['nullable', 'required_if:acompanhante,1'],
            'acompanhante_telefone' => ['nullable', 'required_if:acompanhante,1'],
            'cpf_acompanhante' => ['nullable'],
            'obs' => ['nullable'],
            'telefone2' => ['nullable'],
            'telefone3' => ['nullable'],
            'sexo' => ['nullable', 'required_if:teleatendimento,1'],
            'estado' => ['nullable'],
            'cidade' => ['nullable'],
            'bairro' => ['nullable'],
            'numero' => ['nullable'],
            'complemento' => ['nullable'],
            'cpf' => ['nullable', 'required_if:teleatendimento,1'],
            'cep' => ['nullable'],
            'rua' => ['nullable'],
            'solicitante_agenda' => ['nullable'],
            'carteirinha_id' => ['nullable'],
            'compromisso_id' => ['nullable', 'exists:compromissos,id'],
            'proximo_horario_existe' => ['nullable'],
            'tipo_inserir' => ['required'],
            'inst_prestador_id' => ['required'],
            'teleatendimento' => ['nullable'],
            'lista_paciente' => ['nullable'],
            'email' => ['nullable', 'required_if:teleatendimento,1'],
        ];
    }
}
