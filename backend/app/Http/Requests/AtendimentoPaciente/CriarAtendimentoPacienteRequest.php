<?php

namespace App\Http\Requests\AtendimentoPaciente;

use Illuminate\Foundation\Http\FormRequest;

class CriarAtendimentoPacienteRequest extends FormRequest
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
            'motivo_atendimento_id' => ['required'],
            'descricao' => ['required'],
        ];
    }
}
