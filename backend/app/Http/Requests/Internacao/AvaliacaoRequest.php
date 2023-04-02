<?php

namespace App\Http\Requests\Internacao;

use Illuminate\Foundation\Http\FormRequest;

class AvaliacaoRequest extends FormRequest
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
            'medico_id' => ['nullable'],
            'especialidade_id' => ['nullable'],
            'avaliacao_id' =>  ['nullable'],
            'avaliacao' => ['required'],
        ];
    }
}
