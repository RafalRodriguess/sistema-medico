<?php

namespace App\Http\Requests\Faturamento;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PesquisaFiltrosRequest extends FormRequest
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
            'data_inicio' => ['required' , 'date'],
            'data_fim' => ['required', 'date'],
            'convenios.*' => ['required', 'exists:convenios,id'],
            'prestadores.*' => ['required', 'exists:prestadores,id']
        ];
    }
}
