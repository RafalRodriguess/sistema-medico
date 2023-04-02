<?php

namespace App\Http\Requests\EstoqueEntrada;

use App\Instituicao;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriarEstoqueEntradaRequest extends FormRequest
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
        $instituicao = Instituicao::find(request()->session()->get('instituicao'));
        return [
            'id_tipo_documento' => ['required','exists:tipos_documentos,id'],
            'id_estoque' => ['required','exists:estoques,id'],
            'consignado' => ['required'],
            'contabiliza' => ['required'],
            'numero_documento' => ['nullable','string'],
            'serie' => ['nullable','string'],
            'id_fornecedor'=>['required','exists:pessoas,id'],
            'data_emissao'=>['required'],
            'data_hora_entrada'=>['required'],

            'produtos' => ['required', 'array', 'min:1'],
            'produtos.*.id_entrada_produto' => ['nullable'],
            'produtos.*.id' => ['required', Rule::exists('produtos', 'id')->where('instituicao_id', $instituicao->id)],
            'produtos.*.quantidade' => ['required', 'min:0'],
            'produtos.*.lote' => ['required'],
            'produtos.*.valor' => ['required', 'min:0'],
            'produtos.*.valor_custo' => ['required', 'min:0'],
            'produtos.*.validade' => ['nullable', 'date']
        ];
    }

    public function messages()
    {
        return [
            'produtos.*.min' => 'O valor mínimo é 0',
            'produtos.*.*' => 'Valor inválido',
            'produtos.*.required' => 'Campo obrigatório',

            '*.required' => 'Campo obrigatório',
            '*.*' => 'Valor inválido',
        ];
    }
}
