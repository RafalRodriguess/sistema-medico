<?php

namespace App\Http\Requests\CaixaCirurgico;

use Illuminate\Foundation\Http\FormRequest;

class CriarCaixaCirurgicoRequest extends FormRequest
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
            'descricao' => ['required'],
            'descricao_resumida' => ['required'],
            'qtd' => ['required', 'gte:0'],
            'tempo_esterelizar' => ['required', 'gte:0'],
            'ativo' => ['nullable'],
        ];
    }
}
