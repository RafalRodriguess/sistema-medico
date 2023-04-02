<?php

namespace App\Http\Requests\AgendamentosListaEspera;

use Illuminate\Foundation\Http\FormRequest;

class CriarAgendamentoListaEsperaRequest extends FormRequest
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
            'paciente_id' => ['required', 'exists:pessoas,id'],
            'convenio_id' => ['nullable', 'exists:convenios,id'],
            'prestador_id' => ['nullable', 'exists:prestadores,id'],
            'especialidade_id' => ['nullable', 'exists:especialidades,id'],
            'obs' => ['nullable'],
        ];
    }
}
