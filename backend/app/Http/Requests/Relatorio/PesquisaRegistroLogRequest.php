<?php

namespace App\Http\Requests\Relatorio;

use Illuminate\Foundation\Http\FormRequest;

class PesquisaRegistroLogRequest extends FormRequest
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
            'data_inicio' => ['required'],
            'data_fim' => ['required'],
            'tipo' => ['required'],
            'registro_id' => ['nullable'],
        ];
    }
}
