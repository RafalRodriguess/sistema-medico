<?php

namespace App\Http\Requests\EstoqueInventario;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriarEstoqueInventarioRequest extends FormRequest
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
        $instituicao = request()->session()->get('instituicao');
        return [
            'estoque_id' => ['required','exists:estoques,id'],
            'data' => ['required'],
            'hora' => ['required'],
            'aberta' => ['required'],
            'tipo_contagem' => ['required', Rule::in(['Geral do Estoque','Apenas Alguns Produtos'])],

            'produtos.*.id' => ['required', 'numeric', Rule::exists('produtos', 'id', function($query) use ($instituicao) {
                $query->where('instituicao_id', $instituicao);
            })],
            'produtos.*.quantidade' => ['required', 'numeric'],
            'produtos.*.lote' => ['required'],
            'produtos.*.quantidade_inventario'=>['required', 'numeric']

        ];
    }
}
