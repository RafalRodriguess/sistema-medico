<?php

namespace App\Http\Requests\ExamePaciente;

use Illuminate\Foundation\Http\FormRequest;

class CriarExamePaciente extends FormRequest
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
            'obs_exame' => ['required'],
            'exame_id' => ['nullable', 'exists:exames_paciente,id'],
            'compartilhado' => ['nullable'],
        ];
    }
}
