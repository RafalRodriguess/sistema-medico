<?php

namespace App\Http\Requests\ConclusaoPaciente;

use Illuminate\Foundation\Http\FormRequest;

class CriarConclusaoPacienteRequest extends FormRequest
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
            'obs_conclusao' => ['required'],
            'conclusao_id' => ['nullable', 'exists:conclusoes_paciente,id'],
            'motivo_conclusao_id' => ['required', 'exists:motivos_conclusoes,id'],
            'compartilhado' => ['nullable'],
        ];
    }
}
