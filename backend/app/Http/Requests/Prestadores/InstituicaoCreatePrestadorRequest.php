<?php

namespace App\Http\Requests\Prestadores;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Prestador;

class InstituicaoCreatePrestadorRequest extends FormRequest
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
            'ativo' => ['nullable', 'integer'],
            'tipo_prontuario' => ['nullable'],
            'crm' => ['nullable', 'required_if:tipo,2'],
            'telefone' => ['required'],
            'telefone2' => ['nullable'],
            'nome' => ['required','string'],
            'instituicao_usuario_id' => ['nullable','exists:instituicao_usuarios,id'],
            
            'exibir_data' => ['nullable'],
            'resumo_tipo' => ['nullable'],
            'exibir_titulo_paciente' => ['nullable'],
            'whatsapp_enviar_confirm_agenda' => ['nullable'],
            'whatsapp_receber_agenda' => ['nullable'],
            'sancoop_user_coperado' => ['nullable'],

            'excessao.*.procedimento_id' => ['nullable', 'exists:procedimentos,id'],
            'excessao.*.prestador_faturado_id' => ['nullable', 'exists:instituicoes_prestadores,id'],

            // *** Se o prestador tiver personalidade fisica ***
            'nascimento' => [
                'required',
                'string',
            ],
            'sexo' => [
                'required',
                Rule::in(array_keys(Prestador::opcoes_sexo))
            ],
            'cpf' => [
                'nullable',
                'required_if:tipo,2',
                'string',
            ],
            'identidade' => [
                'nullable',
                'string',
            ],
            'identidade_orgao_expedidor' => [
                'nullable',
                'string',
            ],
            'identidade_uf' => [
                'nullable',
                'string',
            ],
            'identidade_data_expedicao' => [
                'nullable',
                'string',
            ],
            'numero_cartao_sus' => [
                'nullable',
                'string',
            ],
            'nome_da_mae' => [
                'nullable',
                'string',
            ],
            'nome_do_pai' => [
                'nullable',
                'string',
            ],
            'naturalidade' => [
                'nullable',
                'string',
            ],
            'nacionalidade' => [
                'nullable',
                'string',
            ],
            'vinculos' => [
                'required',
                'array',
            ],
            'carga_horaria_mensal' => [
                'nullable',
                'integer',
            ],
            'tipo' => [
                'required',
                'integer',
            ],
            // *** Se o prestador tiver personalidade Jurídica ***
            'cnpj' => [
                'nullable',
                // 'required_if:personalidade,2',
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
                'nullable','exclude_if:personalidade,1',
                'string',
            ],
            'agencia' => [
                'nullable',
                'exclude_if:personalidade,1',
                'string',
            ],
            'conta_bancaria' => [
                'nullable',
                'exclude_if:personalidade,1',
                'string',
            ],
            'cep' => [
                'nullable',
                'exclude_if:personalidade,1',
                'string',
            ],
            'estado' => [
                'nullable',
                'exclude_if:personalidade,1',
                'string',
            ],
            'cidade' => [
                'nullable',
                'exclude_if:personalidade,1',
                'string',
            ],
            'bairro' => [
                'nullable',
                'exclude_if:personalidade,1',
                'string',
            ],
            'rua' => [
                'nullable',
                'exclude_if:personalidade,1',
                'string',
            ],
            'numero' => [
                'nullable',
                'exclude_if:personalidade,1',
                'string',
            ],
            // *** Se o prestador for do tipo Médico ***
            'tipo_conselho_id' => [
                'nullable',
                'required_if:tipo,2',
                'integer'
            ],
            'conselho_uf' => [
                'nullable',
                'required_with:tipo_conselho_id',
                'string',
            ],
            // if(tipo == 2 || tipo == 3 || tipo == 6 || tipo == 7 || tipo == 8 || tipo == 9 || tipo == 10 || tipo == 11){
            'especialidades' => [
                'nullable',
                'required_if:tipo,2|tipo,8|tipo,3|tipo,6|tipo,7|tipo,8|tipo,9|tipo,10|tipo,15',
                'array',
            ],
            'anestesista' => [
                'nullable',
                'exclude_unless:tipo,2',
                'exclude_unless:tipo,2',
                'integer'
            ],
            'auxiliar' => [
                'nullable',
                'exclude_unless:tipo,2',
                'exclude_unless:tipo,2',
                'integer'
            ],
            // *** Se o prestador tiver vínculo cooperativo ***
            'numero_cooperativa' => [
                'nullable',
                Rule::requiredIf(function(){
                    if($this['vinculos']){
                        if(in_array(1, $this['vinculos'])) return true;
                    }
                    return false;
                }),
                'string',
            ],
            // *** Se o prestador tiver vinculo de funcionário ou estagiário ***
            'pis' => [
                'nullable',
                'string'
            ],
            'pasep' => [
                'nullable',
                'string'
            ],
            'proe' => [
                'nullable',
                'string'
            ],
            'nir' => [
                'nullable',
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
            'especializacoes' => [
                'array',
                'nullable'
            ],
            'especializacoes.*.especializacoes_id' => [
                'numeric',
                'required'
            ],
            'continue' => ['nullable'],
            'telemedicina_integrado' => ['nullable'],
            'email' => ['required','string'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Campo obrigatório',
            'required_if' => 'Campo obrigatório',
            'required_with' => 'Campo obrigatório',

            'conselho_uf.required_with' => 'A UF do conselho é obrigatória quando um conselho é selecionado'
        ];
    }
}
