<?php

namespace App\Http\Requests\Totens;

use Illuminate\Foundation\Http\FormRequest;

class InstituicaoCreateTotem extends FormRequest
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
            'nome' => ['string','required'],
            'descricao' => ['string', 'nullable'],
            'filas' => ['array', 'nullable'],
            'filas.filas_triagem_id.*' => ['integer', 'required']
        ];
    }
}
