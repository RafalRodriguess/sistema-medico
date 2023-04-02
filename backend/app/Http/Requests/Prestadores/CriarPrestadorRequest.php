<?php

namespace App\Http\Requests\Prestadores;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Prestador;

class CriarPrestadorRequest extends FormRequest
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

    public function rules()
    {
        return [
            'ativo' => ['nullable', 'integer'],
            'nome' => ['required','string'],

            'exibir_data' => ['nullable'],
            'exibir_titulo_paciente' => ['nullable'],
            // *** Se o prestador tiver personalidade fisica ***
            'nascimento' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'string',
            ],
            'sexo' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'numeric',
                Rule::in(array_keys(Prestador::opcoes_sexo))
            ],
            'cpf' => [
                'nullable',
                'required_if:personalidade,1',
                Rule::unique('prestadores','cpf')->whereNull('deleted_at'),
                'exclude_if:personalidade,2',
                'string',
            ],
            'identidade' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'string',
            ],
            'identidade_orgao_expedidor' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'string',
            ],
            'identidade_uf' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'string',
            ],
            'identidade_data_expedicao' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'string',
            ],
            'numero_cartao_sus' => [
                'nullable',
                'exclude_if:personalidade,2',
                'string',
            ],
            'nome_da_mae' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'string',
            ],
            'nome_do_pai' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'string',
            ],
            'naturalidade' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'string',
            ],
            'nacionalidade' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'string',
            ],
            'vinculos' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'array',
            ],
            'carga_horaria_mensal' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'integer',
            ],
            'tipo' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'integer',
            ],
            // *** Se o prestador tiver personalidade Jurídica ***
            'cnpj' => [
                'nullable',
                'required_if:personalidade,2',
                Rule::unique('prestadores','cnpj')->whereNull('deleted_at'),
                'exclude_if:personalidade,1',
                'string',
            ],
            'razao_social' => [
                'nullable',
                'required_if:personalidade,2',
                'exclude_if:personalidade,1',
                'string',
            ],
            'nome_banco' => [
                'nullable',
                'required_if:personalidade,2',
                'exclude_if:personalidade,1',
                'string',
            ],
            'agencia' => [
                'nullable',
                'required_if:personalidade,2',
                'exclude_if:personalidade,1',
                'string',
            ],
            'conta_bancaria' => [
                'nullable',
                'required_if:personalidade,2',
                'exclude_if:personalidade,1',
                'string',
            ],
            'cep' => [
                'nullable',
                'required_if:personalidade,2',
                'exclude_if:personalidade,1',
                'string',
            ],
            'estado' => [
                'nullable',
                'required_if:personalidade,2',
                'exclude_if:personalidade,1',
                'string',
            ],
            'cidade' => [
                'nullable',
                'required_if:personalidade,2',
                'exclude_if:personalidade,1',
                'string',
            ],
            'bairro' => [
                'nullable',
                'required_if:personalidade,2',
                'exclude_if:personalidade,1',
                'string',
            ],
            'rua' => [
                'nullable',
                'required_if:personalidade,2',
                'exclude_if:personalidade,1',
                'string',
            ],
            'numero' => [
                'nullable',
                'required_if:personalidade,2',
                'exclude_if:personalidade,1',
                'string',
            ],
            // *** Se o prestador for do tipo Médico ***
            'conselho_id' => [
                'nullable',
                'required_if:tipo,2',
                'exclude_unless:tipo,2',
                'integer'
            ],
            'especialidades' => [
                'nullable',
                'required_if:tipo,2',
                'exclude_unless:tipo,2',
                'array',
            ],
            'anestesista' => [
                'nullable',
                'exclude_unless:tipo,2',
                'integer'
            ],
            'auxiliar' => [
                'nullable',
                'exclude_unless:tipo,2',
                'integer'
            ],
            // *** Se o prestador tiver vínculo cooperativo ***
            'numero_cooperativa' => [
                'nullable',
                Rule::requiredIf(function(){
                    if($this['vinculo']){
                        if(in_array('1', $this['vinculo'])) return true;
                    }
                    return false;
                }),
                'exclude_if:personalidade,2',
                'string',
            ],
            // *** Se o prestador tiver vinculo de funcionário ou estagiário ***
            'pis' => [
                'nullable',
                'exclude_if:personalidade,2',
                'string'
            ],
            'pasep' => [
                'nullable',
                'exclude_if:personalidade,2',
                'string'
            ],
            'nir' => [
                'nullable',
                'exclude_if:personalidade,2',
                'string'
            ],
            'proe' => [
                'nullable',
                'exclude_if:personalidade,2',
                'string'
            ],
            // *** Opcional para qualquer modalidade
            'documentos' => [
                'nullable',
                'exclude_without:documentos',
                'array',
            ],
            'documentos.*.tipo' => [
                'nullable',
                'required_with:documentos',
                'integer'
            ],
            'documentos.*.descricao' => [
                'nullable',
                'required_with:documentos',
                'string'
            ],
            'documentos.*.arquivo' => [
                'nullable',
                'required_with:documentos',
                'file',
                'mimes:png,jpg,jpeg,csv,txt,xlx,xls,pdf',
                'max:2048'
            ],
        ];
    }

    public function messages()
    {
        return [
            'nome.required'=>'O nome do prestador é obrigatório',
            'nascimento.required_if'=>'A Data de Nascimento é obrigatória',
            'cpf.required_if'=>'O CPF é obrigatório',
            'sexo.required_if'=>'Campo obrigatório',
            'identidade.required_if'=>'o RG é obrigatório',
            'identidade_orgao_expedidor.required_if'=>'O Orgão Expedidor é obrigatório',
            'identidade_uf.required_if'=>'O UF é obrigatório',
            'identidade_data_expedicao.required_if'=>'A Data de Expedição é obrigatória',
            'nome_da_mae.required_if'=>'O Nome da Mãe é obrigatório',
            'nome_do_pai.required_if'=>'O Nome do Pai é obrigatório',
            'nacionalidade.required_if'=>'A nacionalidade é obrigatória',
            'naturalidade.required_if'=>'A Naturalidade é obrigatória',
            // 'carga_horaria_mensal.required_if'=>'Campo obrigatório',
            // 'vinculo.required_if'=>'Pelo menos um vínculo é obrigatório',
            // 'tipo.required_if'=>'A Atuação do Prestador é obrigatória',
            'cpf.unique'=> 'CPF já registrado em outro prestador',
            'cnpj.unique'=> 'CNPJ já registrado em outro prestador',

            // 'conselho_id.required_if'=>'Obrigátorio para Médico',
            // 'especialidade.required_if'=>'Pelo menos uma especialidade é obrigatória para o Médico',

            // 'numero_cooperativa.required' => 'Obrigatório para vínculo cooperativo',

            'documentos.*.tipo.required_with' => 'O Tipo do documento é obrigatório',
            'documentos.*.descricao.required_with' => 'A Descrição do documento é obrigatória',
            'documentos.*.arquivo.required_with' => 'O Arquivo do documento é obrigatório'
        ];
    }
}
