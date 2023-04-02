<?php

namespace App\Http\Requests\Procedimentos;

use Illuminate\Foundation\Http\FormRequest;

class CreateProcedimentoInstitucaoRequest extends FormRequest
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
            'descricao' => ['required'],
            'tipo' => ['required'],
            'grupo_id' => ['required'],
            'odontologico' => ['nullable'],
            'possui_regiao' => ['nullable'],
            'tipo_limpeza' => ['nullable'],
            'sexo' => ['nullable'],
            'pacote' => ['nullable'],
            'qtd_maxima' => ['nullable'],
            'tipo_servico' => ['nullable'],
            'tipo_consulta' => ['nullable'],
            'recalcular' => ['nullable'],
            'busca_ativa' => ['nullable'],
            'parto' => ['nullable'],
            'diaria_uti_rn' => ['nullable'],
            'md_mt' => ['nullable'],
            'cod' => ['nullable'],
            'pesquisa_satisfacao' => ['nullable'],
            'exige_quantidade' => ['nullable'],
            'valor_custo' => ['nullable'],
            'n_cobrar_agendamento' => ['nullable'],
            'duracao_atendimento' => ['nullable'],
            'vinculo_tuss_id' => ['nullable', 'exists:vinculo_tuss,id'],
            'tipo_guia' => ['nullable', 'numeric'],
            'compromisso_id' => ['nullable', 'exists:compromissos,id'],
        ];
    }
}
