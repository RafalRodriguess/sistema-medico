<?php

namespace App\Http\Requests\Produtos;

use Illuminate\Foundation\Http\FormRequest;

class EditarPromocaoProdutoRequest extends FormRequest
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
     * greather than
     * greather than or equals (gt)
     * lesser than
     * lesser than or equals
     * 
     * - tamanho (quantidade de caracretes, tamanho de arequiv)
     * min
     * max
     *
     * @return array
     */
    public function rules()
    {
        return [
            'preco_promocao' => ['required', 'numeric', 'gt:0', "lt:{$this->produto->preco}"],
            'promocao_inicio' => ['required', 'date'],
            'promocao_final' => ['required', 'date', 'after:promocao_inicio']
        ];
    }
}
