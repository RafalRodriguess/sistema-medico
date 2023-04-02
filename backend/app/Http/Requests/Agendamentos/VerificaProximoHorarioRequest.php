<?php

namespace App\Http\Requests\Agendamentos;

use Illuminate\Foundation\Http\FormRequest;

class VerificaProximoHorarioRequest extends FormRequest
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
            'hora_agenda' => ['required'],
            'hora_agenda_final' => ['required'],
            'data_agenda' => ['required'],
            'inst_prest_id' => ['required', 'exists:instituicoes_agenda,id'],
        ];
    }
}
