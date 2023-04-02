<?php

namespace App\Http\Requests\EstoqueEntrada;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditarEstoqueEntradaRequest extends FormRequest
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

            'id_tipo_documento' => ['required','exists:tipos_documentos,id'],
            'id_estoque' => ['required','exists:estoques,id'],
            'consignado' => ['required'],
            'contabiliza' => ['required'],
            'numero_documento' => ['nullable','string'],
            'serie' => ['nullable','string'],
            'id_fornecedor'=>['required','exists:pessoas,id'],
            'data_emissao'=>['required'],
            'data_hora_entrada'=>['required'],

            'produtos.*.id' => ['nullable'],
            'produtos.*.quantidade' => ['nullable'],
            'produtos.*.lote' => ['nullable'],
        ];
    }
}
