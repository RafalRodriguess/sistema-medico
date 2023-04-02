<?php

namespace App\Http\Requests\Odontologico;

use Illuminate\Foundation\Http\FormRequest;

class ConcluirProcedimentoOdontologicoRequest extends FormRequest
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
            'orcamento.*' => ['nullable', 'exists:odontologico_itens_paciente,id'],
            'prestador_id' => ['required', 'exists:prestadores,id']
        ];
    }
}
