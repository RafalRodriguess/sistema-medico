<?php

namespace App\Http\Requests\Cartoescredito;

use Illuminate\Foundation\Http\FormRequest;

class CriarCartaoCreditoRequest extends FormRequest
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
            'bandeira' => ['required'],
            'limite' => ['required'],
            'fechamento' => ['required'],
            'vencimento' => ['required'],
        ];
    }
}
