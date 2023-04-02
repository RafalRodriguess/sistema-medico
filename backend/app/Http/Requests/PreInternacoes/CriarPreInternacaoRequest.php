<?php

namespace App\Http\Requests\PreInternacoes;

use Illuminate\Foundation\Http\FormRequest;

class CriarPreInternacaoRequest extends FormRequest
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
            'paciente_id' => ['required'],

            'paciente_nome' => ['required'],

            'previsao' => ['nullable'],
            'origem_id' => ['nullable'],
            'medico_id' => ['nullable'],
            'especialidade_id' => ['nullable'],
            'acomodacao_id' => ['nullable'],
            'unidade_id' => ['nullable'],
            'acompanhante' => ['nullable'],
            'tipo_internacao' => ['nullable'],
            'leito_id' => ['nullable'],
            'reserva_leito' => ['nullable'],
            'observacao' => ['nullable'],
            'cid_id' => ['nullable'],

            'possui_responsavel' => ['nullable'],

            'parentesco_responsavel' => ['required_if:possui_responsavel,1'],
            'nome_responsavel' => ['required_if:possui_responsavel,1'],
            'estado_civil_responsavel' => ['nullable'],
            'profissao_responsavel' => ['nullable'],
            'nacionalidade_responsavel' => ['nullable'],
            'telefone1_responsavel' => ['required_if:possui_responsavel,1'],
            'telefone2_responsavel' => ['nullable'],
            'identidade_responsavel' => ['nullable'],
            'cpf_responsavel' => ['nullable'],
            'contato_responsavel' => ['nullable'],
            'cep_responsavel' => ['nullable'],
            'endereco_responsavel' => ['nullable'],
            'numero_responsavel' => ['nullable'],
            'complemento_responsavel' => ['nullable'],
            'bairro_responsavel' => ['nullable'],
            'cidade_responsavel' => ['nullable'],
            'uf_responsavel' => ['nullable'],

            'itens.*.convenio' => ['nullable', 'exists:convenios,id'],
            'itens.*.procedimento' => ['required_with:itens.*.convenio', 'exists:procedimentos_instituicoes_convenios,id'],
            'itens.*.quantidade_procedimento' => ['nullable'],
            'itens.*.valor' => ['nullable'],

			
        ];
    }
}
