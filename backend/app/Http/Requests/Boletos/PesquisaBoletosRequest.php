<?php

namespace App\Http\Requests\Boletos;

use Illuminate\Foundation\Http\FormRequest;

class PesquisaBoletosRequest extends FormRequest
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
            'data_inicio' => ['required', 'date'],
            'data_fim' => ['required', 'date', 'after_or_equal:data_inicio'],
            'instituicoes.*' => ['nullable', 'exists:instituicoes,id'],
        ];
    }
}
