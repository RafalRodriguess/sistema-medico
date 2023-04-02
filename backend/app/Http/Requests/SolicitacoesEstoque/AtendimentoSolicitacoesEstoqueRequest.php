<?php

namespace App\Http\Requests\SolicitacoesEstoque;

use App\Instituicao;
use App\Rules\LoteEstoqueExiste;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AtendimentoSolicitacoesEstoqueRequest extends FormRequest
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
            'produtos' => [
                'nullable',
                'array'
            ],
            'produtos.*.id' => [
                'required',
                'numeric'
            ],
            'produtos.*.motivos_divergencia_id' => [
                'nullable',
                'numeric'
            ],
            'produtos.*.confirma_item' => [
                'nullable',
                'in:on'
            ],
            // Produtos atendidos
            'produtos_recebidos' => [
                'nullable',
                'array'
            ],
            'produtos_recebidos.*.quantidade' => [
                'required',
                'numeric', 'min:0'
            ],
            'produtos_recebidos.*.codigo_de_barras' => [
                'nullable',
                'string'
            ],
            'produtos_recebidos.*.id_entrada_produto' => [
                'required'
            ]
        ];
    }
}
