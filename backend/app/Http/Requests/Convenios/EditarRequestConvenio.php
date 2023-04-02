<?php

namespace App\Http\Requests\Convenios;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Convenio;
use App\ConveniosControleRetorno;
use Illuminate\Http\Request;

class EditarRequestConvenio extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'nome' => ['required', Rule::unique('convenios', 'nome')->ignore($this->convenio)->whereNull('deleted_at')->where('instituicao_id', $request->session()->get('instituicao'))],
            'descricao' => ['nullable'],
            'razao_social' => ['nullable'],
            'dt_inicio_contrato' => ['nullable'],
            'responsavel' => ['nullable'],
            'cargo_responsavel' => ['nullable'],
            'email' => ['nullable'],
            'endereco' => ['nullable'],
            'fone_contato' => ['nullable'],
            'apresentacoes_convenio_id' => ['nullable', 'integer', 'exists:apresentacoes_convenio,id'],
            'email_glossas' => ['nullable', 'email', 'string'],
            'cep' => ['nullable', 'string'],
            'cgc' => ['nullable', 'string'],
            'inscricao_municipal' => ['nullable', 'string'],
            'inscricao_estadual' => ['nullable', 'string'],
            'tipo_convenio' => ['nullable', Rule::in(array_keys(Convenio::opcoes_tipo_convenio))],
            'guia_obrigatoria' => ['nullable', Rule::in(array_keys(Convenio::opcoes_guia_obrigatoria))],
            'categoria_obrigatoria' => ['nullable', 'in:0,1'],
            'abate_devolucao' => ['nullable', 'in:0,1'],
            'filantropia' => ['nullable', 'in:0,1'],
            'fatura_p_alta' => ['nullable', 'in:0,1'],
            'desc_conta' => ['nullable', 'in:0,1'],
            'forma_agrupamento' => ['nullable', Rule::in(array_keys(Convenio::opcoes_forma_agrupamento))],
            'fonte_de_remuneracao' => ['nullable', Rule::in(array_keys(Convenio::opcoes_fonte_de_remuneracao))],
            'tipo_cobranca_oncologia' => ['nullable', Rule::in(array_keys(Convenio::opcoes_tipo_cobranca_oncologia))],
            'permitir_atendimento_ambulatorial' => ['nullable', 'in:on'],
            'permitir_atendimento_externo' => ['nullable', 'in:on'],
            'fechar_conta_amb_sem_impressao' => ['nullable', 'in:on'],
            'retorno_atendimento_ambulatorio' => ['nullable', 'numeric'],
            'retorno_atendimento_externo' => ['nullable', 'numeric'],
            'retorno_atendimento_urgencia' => ['nullable', 'numeric'],
            'registro_ans' => ['nullable', 'numeric'],
            'carteira_pede' => ['nullable', 'in:0,1'],
            'carteira_verif_elig' => ['nullable', 'in:0,1'],
            'carteira_obg' => ['nullable', 'in:0,1'],
            'limite_contas_pre_remessa' => ['nullable', 'numeric', 'min:0'],
            'quantidade_alerta_faixa' => ['nullable', 'numeric', 'min:0'],
            'configuracoes_retorno' => ['array', 'nullable'],
            'configuracoes_retorno.*.campo' => ['nullable', 'numeric', Rule::in(array_keys(ConveniosControleRetorno::opcoes_campos_retorno))],
            'configuracoes_retorno.*.grupo' => ['nullable', 'numeric', Rule::in(ConveniosControleRetorno::tipos_grupos_atendimento)],
            'pessoas_id' => [
                'nullable',
                Rule::exists('pessoas', 'id')->where(function ($query) {
                    return $query->where('tipo', '=', 3);
                })
            ],
            'excecoes' => ['array', 'nullable'],
            'excecoes.*.procedimentos_id' => ['required', 'exists:procedimentos,id'],
            'ativo' => ['nullable'],
            'cnpj'=> ['nullable', 'min:18', 'max:18'],
            'carteirinha_obg' => ['nullable'],
            'aut_obrigatoria' => ['nullable'],
            'divisao_tipo_guia' => ['nullable', 'numeric'],
            'versao_tiss_id' => ['nullable', 'exists:versoes_tiss,id'],
            'imagem' => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
            'possui_terceiros' => ['nullable'],
        ];
    }

    public function messages()
    {
        return [
            'excecoes.*.procedimentos_id.*' => 'Um ou mais procedimentos não são válidos',
        ];
    }
}
