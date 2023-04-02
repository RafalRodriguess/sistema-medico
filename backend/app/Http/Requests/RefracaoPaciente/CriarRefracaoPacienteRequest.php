<?php

namespace App\Http\Requests\RefracaoPaciente;

use Illuminate\Foundation\Http\FormRequest;

class CriarRefracaoPacienteRequest extends FormRequest
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
            'refracao_id' => ['nullable', 'exists:refracao_paciente,id'],
            'refracao_atual.ref_atual_od_esferico' => ['nullable'],
            'refracao_atual.ref_atual_od_cilindrico' => ['nullable'],
            'refracao_atual.ref_atual_od_eixo' => ['nullable'],
            'refracao_atual.ref_atual_od_adicao' => ['nullable'],
            'refracao_atual.ref_atual_oe_esferico' => ['nullable'],
            'refracao_atual.ref_atual_oe_cilindrico' => ['nullable'],
            'refracao_atual.ref_atual_oe_eixo' => ['nullable'],
            'refracao_atual.ref_atual_oe_adicao' => ['nullable'],
            'refracao_atual.ref_atual_obs' => ['nullable'],
            'acuidade_visual.acuidade_od_sc' => ['nullable'],
            'acuidade_visual.acuidade_od_sc_ck' => ['nullable'],
            'acuidade_visual.acuidade_od_cc' => ['nullable'],
            'acuidade_visual.acuidade_od_cc_ck' => ['nullable'],
            'acuidade_visual.acuidade_oe_sc' => ['nullable'],
            'acuidade_visual.acuidade_oe_sc_ck' => ['nullable'],
            'acuidade_visual.acuidade_oe_cc' => ['nullable'],
            'acuidade_visual.acuidade_oe_cc_ck' => ['nullable'],
            'refracao_estatica.ref_estatica_l_od_esferico' => ['nullable'],
            'refracao_estatica.ref_estatica_l_od_cilindrico' => ['nullable'],
            'refracao_estatica.ref_estatica_l_od_eixo' => ['nullable'],
            'refracao_estatica.ref_estatica_l_od_av' => ['nullable'],
            'refracao_estatica.ref_estatica_l_od_av_ck' => ['nullable'],
            'refracao_estatica.ref_estatica_l_oe_esferico' => ['nullable'],
            'refracao_estatica.ref_estatica_l_oe_cilindrico' => ['nullable'],
            'refracao_estatica.ref_estatica_l_oe_eixo' => ['nullable'],
            'refracao_estatica.ref_estatica_l_oe_av' => ['nullable'],
            'refracao_estatica.ref_estatica_l_oe_av_ck' => ['nullable'],
            'refracao_estatica.ref_estatica_p_od_adicao' => ['nullable'],
            'refracao_estatica.ref_estatica_p_od_jaeger' => ['nullable'],
            'refracao_estatica.ref_estatica_p_oe_adicao' => ['nullable'],
            'refracao_estatica.ref_estatica_p_oe_jaeger' => ['nullable'],
            'refracao_dinamica.ref_dinamica_l_od_esferico' => ['nullable'],
            'refracao_dinamica.ref_dinamica_l_od_cilindrico' => ['nullable'],
            'refracao_dinamica.ref_dinamica_l_od_eixo' => ['nullable'],
            'refracao_dinamica.ref_dinamica_l_od_av' => ['nullable'],
            'refracao_dinamica.ref_dinamica_l_od_av_ck' => ['nullable'],
            'refracao_dinamica.ref_dinamica_l_oe_esferico' => ['nullable'],
            'refracao_dinamica.ref_dinamica_l_oe_cilindrico' => ['nullable'],
            'refracao_dinamica.ref_dinamica_l_oe_eixo' => ['nullable'],
            'refracao_dinamica.ref_dinamica_l_oe_av' => ['nullable'],
            'refracao_dinamica.ref_dinamica_l_oe_av_ck' => ['nullable'],
            'refracao_dinamica.ref_dinamica_p_od_adicao' => ['nullable'],
            'refracao_dinamica.ref_dinamica_p_od_jaeger' => ['nullable'],
            'refracao_dinamica.ref_dinamica_p_oe_adicao' => ['nullable'],
            'refracao_dinamica.ref_dinamica_p_oe_jaeger' => ['nullable'],
            'prescricao_oculos.prescricao_od_esferico' => ['nullable'],
            'prescricao_oculos.prescricao_od_cilindrico' => ['nullable'],
            'prescricao_oculos.prescricao_od_eixo' => ['nullable'],
            'prescricao_oculos.prescricao_od_adicao' => ['nullable'],
            'prescricao_oculos.prescricao_oe_esferico' => ['nullable'],
            'prescricao_oculos.prescricao_oe_cilindrico' => ['nullable'],
            'prescricao_oculos.prescricao_oe_eixo' => ['nullable'],
            'prescricao_oculos.prescricao_oe_adicao' => ['nullable'],
            'prescricao_oculos.prescricao_dp' => ['nullable'],
            'prescricao_oculos.prescricao_obs' => ['nullable'],
        ];
    }
}
