<?php

namespace App\Http\Requests\SolicitacoesEstoque;

use Illuminate\Foundation\Http\FormRequest;
use App\SolicitacaoEstoque;
use Illuminate\Validation\Rule;
use App\Instituicao;

class CreateSolicitacaoEstoque extends FormRequest
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
        $instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        return [
            'destino' => [
                'required',
                Rule::in(array_keys(SolicitacaoEstoque::opcoes_destino))
            ],
            'estoque_origem_id' => [
                'required',
                Rule::exists('estoques', 'id')->where('instituicao_id', $instituicao->id)
            ],
            'urgente' => [
                'nullable',
                'in:on'
            ],
            'observacoes' => [
                'string',
                'nullable'
            ],
            'agendamento_atendimentos_id' => [
                'nullable',
                'required_if:destino,1',
                'exists:agendamento_atendimentos,id'
            ],
            'instituicoes_prestadores_id' => [
                'nullable',
                'required_if:destino,1',
                Rule::exists('instituicoes_prestadores', 'id')->where('instituicoes_id', $instituicao->id)
            ],
            'setores_exame_id' => [
                'nullable',
                Rule::requiredIf(function(){
                    return  request()->destino == 2 &&
                            request()->unidades_internacoes_id == null;
                })
            ],
            'unidades_internacoes_id' => [
                'nullable'
            ],
            'estoque_destino_id' => [
                'nullable',
                'required_if:destino,3',
                // Impedir origem ser destino
                Rule::exists('estoques', 'id')->where('instituicao_id', $instituicao->id)->where('id', '!=', request()->estoque_destino_id)
            ],
            'produtos' => [
                'array',
                'required',
                'min:1'
            ],
            'produtos.*.produtos_id' => [
                'required',
                Rule::exists('produtos', 'id')->where('instituicao_id', $instituicao->id)
            ],
            'produtos.*.quantidade' => [
                'required',
                'numeric',
                'min:1'
            ],

            'solicitacao_id' => ['nullable'],
        ];
    }

    public function messages()
    {
        return [
            'produtos.min' => 'a solicitação deve haver ao menos 1 produto',
            'produtos.required' => 'a solicitação deve haver ao menos 1 produto',
            'produtos.*.produtos_id.required' => 'escolha um produto válido',
            'produtos.*.quantidade.min' => 'não é permitido transferir menos de 1 produto'
        ];
    }
}
