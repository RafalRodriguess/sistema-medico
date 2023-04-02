<?php

namespace App\Http\Requests\Produtos;

use Illuminate\Foundation\Http\FormRequest;

class EditarEstoqueProdutosRequest extends FormRequest
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
            'quantidade' => ['nullable'],
            'estoque_ilimitado' => ['nullable'],
            'permitir_comprar_muitos' => ['nullable'],
        ];
    }
}
