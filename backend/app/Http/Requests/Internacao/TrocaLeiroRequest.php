<?php

namespace App\Http\Requests\Internacao;

use Illuminate\Foundation\Http\FormRequest;

class TrocaLeiroRequest extends FormRequest
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
            'internacao_id' => ['required'],
            'acomodacao_id' => ['required'],
            'unidade_id' => ['required'],
            'leito_id' => ['required'],
        ];
    }
}
