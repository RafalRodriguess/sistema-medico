<?php

namespace App\Http\Requests\EstoqueBaixa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriarEstoqueBaixaRequest extends FormRequest
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
            'estoque_id' => ['required','exists:estoques,id'],
            'setor_id' => ['required','exists:setores_exame,id'],
            'motivo_baixa_id'=>['required','exists:motivo_baixa,id'],
            'data_emissao'=>['required'],
            'data_hora_baixa'=>['required'],
            'produtos' => ['nullable', 'array'],
            'produtos.*.id_entrada_produto' => ['required', 'exists:estoque_entradas_produtos,id'],
            'produtos.*.quantidade' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages()
    {
        return  [
            'required' => 'Campo obrigatório',
            'exists' => 'O valor selecionado é inválido'
        ];
    }
}
