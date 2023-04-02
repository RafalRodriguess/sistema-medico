<?php

namespace App\Http\Requests\EstoqueInventarioProdutos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriarEstoqueInventarioProdutosRequest extends FormRequest
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

            'produto_id' => ['required', 'exists:produtos,id'],
            'quantidade' => ['required'],
            'lote' => ['required'],
        ];
    }
}
