<?php

namespace App\Http\Requests\EstoqueEntradaProdutos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriarEstoqueEntradaProdutosRequest extends FormRequest
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

            'id_produto' => ['required', 'exists:produtos,id'],
            'quantidade' => ['required'],
            'lote' => ['required'],
            'valor' => ['required'],
            'valor_custo' => ['required'],
        ];
    }
}
