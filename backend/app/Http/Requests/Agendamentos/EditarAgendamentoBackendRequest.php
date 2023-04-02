<?php

namespace App\Http\Requests\Agendamentos;

use Illuminate\Foundation\Http\FormRequest;

class EditarAgendamentoBackendRequest extends FormRequest
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
            'total_procedimentos_descricao' => ['nullable'],
            'convenio.*.convenio_agenda' => ['nullable'],
            'convenio.*.procedimento_agenda' => ['nullable'],
            'convenio.*.qtd_procedimento' => ['nullable'],
            'convenio.*.valor' => ['nullable'],
            'convenio.*.desconto' => ['nullable'],
            'obs' => ['nullable']
        ];
    }
}
