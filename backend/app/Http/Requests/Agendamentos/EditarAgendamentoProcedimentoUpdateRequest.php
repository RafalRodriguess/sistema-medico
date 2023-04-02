<?php

namespace App\Http\Requests\Agendamentos;

use Illuminate\Foundation\Http\FormRequest;

class EditarAgendamentoProcedimentoUpdateRequest extends FormRequest
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
            'cod_agendamentos' => ['required']
        ];
    }
}
