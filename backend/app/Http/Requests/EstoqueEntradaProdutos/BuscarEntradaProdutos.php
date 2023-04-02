<?php

namespace App\Http\Requests\EstoqueEntradaProdutos;

use Illuminate\Foundation\Http\FormRequest;

class BuscarEntradaProdutos extends FormRequest
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
            'search' => ['nullable', 'string'],
            'baixa_estoque' => ['nullable', 'numeric'], // Define a baixa de estoque cujas alterações no estoque não serão consideradas
            'entradas_produtos' => ['nullable', 'array'],
            'entradas_produtos.*' => ['numeric', 'required']
        ];
    }
}
