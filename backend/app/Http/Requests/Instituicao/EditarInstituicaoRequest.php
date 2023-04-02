<?php

namespace App\Http\Requests\Instituicao;

use Illuminate\Foundation\Http\FormRequest;

class EditarInstituicaoRequest extends FormRequest
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
            'nome' => ['required'],
            'imagem' => ['nullable'],
            'email'=> ['required', 'email'],
            'telefone'=> ['required'],
            'rua'=> ['required'],
            'numero'=> ['required'],
            'cep'=> ['required'],
            'bairro'=> ['required'],
            'cidade'=> ['required'],
            'estado'=> ['required'],
            'razao_social' => ['required', 'max:255'],
            'cnpj'=> ['required', 'min:18', 'max:18'],
            'inscricao_estadual'=> ['nullable'],
            'inscricao_municipal'=> ['nullable'],
            'cnes'=> ['nullable', 'numeric', 'digits:7'],
            'tipo'=> ['required'],
            'ramo_id'=> ['required'],
            'finalizar_consultorio'=> ['nullable'],
            'possui_faturamento_sancoop'=> ['nullable'],
            'enviar_pesquisa_satisfacao_atendimentos'=> ['nullable'],
            'automacao_whatsapp'=> ['nullable'],
            'sancoop_automacao_envio_guias'=> ['nullable'],
            // 'max_parcela' => ['required'],
            // 'free_parcela' => ['required'],
            // 'valor_parcela' => ['required'],
            // 'taxa_tectotum' => ['required'],
            // 'valor_minimo' => ['numeric','min:0'],
            // 'cartao_entrega' => ['nullable'],
            // 'sincronizacao_agenda' => ['nullable'],
            // 'dinheiro' => ['nullable'],
            'apibb_possui' => ['nullable'],
            'apibb_codigo_cedente' => ['required_if:apibb_possui,1'],
            'apibb_indicador_pix' => ['required_if:apibb_possui,1'],
            'apibb_client_id' => ['required_if:apibb_possui,1'],
            'apibb_client_secret' => ['required_if:apibb_possui,1'],
            'apibb_gw_dev_app_key' => ['required_if:apibb_possui,1'],
            'automacao_whatsapp_regra_envio'=> ['nullable'],
            'kentro_fila_empresa'=> ['nullable'],
            'kentro_msg_confirmacao'=> ['nullable'],
            'kentro_msg_resposta_confirmacao'=> ['nullable'],
            'kentro_msg_resposta_remarcacao'=> ['nullable'],
            'kentro_msg_pesquisa_satisfacao'=> ['nullable'],
            'kentro_msg_resposta_pesquisa_satisfacao'=> ['nullable'],
            'kentro_msg_aniversario'=> ['nullable'],
            'automacao_whatsapp_horario_agenda_prestador'=> ['nullable'],
            'ausente_agenda'=> ['nullable'],
            'p_juros' => ['nullable'],
            'p_multa' => ['nullable'],
            'dias_pagamento' => ['nullable'],
            'desconto_por_procedimento_agenda'=> ['nullable'],
            'possui_convenio_terceiros'=> ['nullable'],
            'codigo_acesso_terceiros'=> ['nullable'],
            'telemedicina_integrado'=> ['nullable'],
            'automacao_whatsapp_botoes'=> ['nullable'],
            'automacao_whatsapp_aniversario'=> ['nullable'],
            'integracao_asaplan'=> ['nullable'],
        ];
    }
}
