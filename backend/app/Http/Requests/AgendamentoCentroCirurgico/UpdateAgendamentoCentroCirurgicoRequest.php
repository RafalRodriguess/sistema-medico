<?php

namespace App\Http\Requests\AgendamentoCentroCirurgico;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateAgendamentoCentroCirurgicoRequest extends FormRequest
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
            'paciente_id_editar' => ['required_if:tipo_paciente,paciente', 'exists:pessoas,id'],
            'tipo_paciente' => ['required', 'in:paciente,ambulatorio,urgencia,internacao'],
            'agendamento_id_editar' => ['required_if:tipo_paciente,ambulatorio', 'exists:agendamentos,id'],
            'urgencia_id_editar' => ['required_if:tipo_paciente,urgencia', 'exists:agendamentos_atendimentos_urgencia,id'],
            'internacao_id_editar' => ['required_if:tipo_paciente,internacao', 'exists:internacoes,id'],

            'acomodacao_editar' => ['required', $this->existsRule($request, 'acomodacoes', 'id')],
            'unidade_internacao_editar' => ['nullable', $this->existsRule($request, 'unidades_internacoes', 'id')],
            'via_acesso_editar' => ['required', $this->existsRule($request, 'vias_acesso', 'id')],
            'anestesista_editar' => ['required', 'exists:prestadores,id'],
            'tipo_anestesia_editar' => ['required', $this->existsRule($request, 'tipos_anestesia', 'id')],
            'pacote_editar' => ['required', Rule::in(['0', '1'])],
            'cid_editar' => ['nullable', 'exists:cids,id'],
            'obs_editar' => ['nullable'],
            'sala_cirurgica_entrada' => ['nullable'],
            'sala_cirurgica_saida' => ['nullable'],
            'anestesia_inicio' => ['nullable'],
            'anestesia_fim' => ['nullable'],
            'cirurgia_inicio' => ['nullable'],
            'cirurgia_fim' => ['nullable'],
            'limpeza_inicio' => ['nullable'],
            'limpeza_fim' => ['nullable'],

            //EQUIPAMENTOS  
            'equipamentos.*.equipamento' => ['nullable', $this->existsRule($request, 'equipamentos', 'id')],
            'equipamentos.*.quantidade' => ['nullable', 'numeric'],
            
            //CAIXAS CIRÚRGICAS
            'caixas_cirurgicas.*.caixa_cirurgica' => ['nullable', $this->existsRule($request, 'caixas_cirurgicos', 'id')],
            'caixas_cirurgicas.*.quantidade' => ['nullable', 'required_with:caixas_cirurgicas.*.caixa_cirurgica', 'numeric'],

            //OUTRAS CIRURGIAS
            'outras_cirurgias.*.cirurgia' => ['nullable', $this->existsRule($request, 'cirurgias', 'id')],
            'outras_cirurgias.*.via_acesso' => ['nullable', 'required_with:outras_cirurgias.*.cirurgia', $this->existsRule($request, 'vias_acesso', 'id')],
            'outras_cirurgias.*.convenio' => ['nullable', 'required_with:outras_cirurgias.*.cirurgia', $this->existsRule($request, 'convenios', 'id')],
            'outras_cirurgias.*.medico' => ['nullable', 'required_with:outras_cirurgias.*.cirurgia', 'exists:prestadores,id'],
            'outras_cirurgias.*.pacote' => ['nullable', 'required_with:outras_cirurgias.*.cirurgia', Rule::in(['0', '1'])],
            'outras_cirurgias.*.tempo' => ['nullable', 'required_with:outras_cirurgias.*.cirurgia', 'numeric'],
            
            //SANGUE E DERIVADOS
            'sangues_derivados.*.sangue_derivado' => ['nullable', $this->existsRule($request, 'sangues_derivados', 'id')],
            'sangues_derivados.*.quantidade' => ['nullable', 'required_with:sangues_derivados.*.sangue_derivado', 'numeric'],
            
            //PRODUTOS
            // 'produtos' => ['nullable', 'array'],
            'produtos.*.id_entrada_produto' => ['nullable'],
            'produtos.*.quantidade' => ['numeric', 'min:0'],
            'produtos.*.opme' => ['nullable'],
            'produtos.*.obs' => ['nullable'],

            'in_page_equipamentos_caixas_cirurgicas' => ['nullable', Rule::in(['0', '1'])],
            'in_page_outras_cirurgias' => ['nullable', Rule::in(['0', '1'])],
            'in_page_sangues_derivados' => ['nullable', Rule::in(['0', '1'])],
            'in_page_produtos' => ['nullable', Rule::in(['0', '1'])],
        ];
    }

    public function messages()
    {
        return [
            'caixas_cirurgicas.*.quantidade.required_with' => 'O campo quantidade é obrigatório e tem que ser maior ou igual a zero (0)',
            'outras_cirurgias.*.via_acesso.required_with' => 'O campo via acesso é obrigatório',
            'outras_cirurgias.*.convenio.required_with' => 'O campo convenio é obrigatório',
            'outras_cirurgias.*.medico.required_with' => 'O campo medico é obrigatório',
            'outras_cirurgias.*.pacote.required_with' => 'O campo pacote é obrigatório',
            'outras_cirurgias.*.tempo.required_with' => 'O campo tempo é obrigatório e tem que ser inteiro',
            'sangues_derivados.*.quantidade.required_with' => 'O campo quantidade é obrigatório e tem que ser maior ou igual a zero (0)',
            'produtos.*.fornecedor.required_with' => 'O campo fornecedor é obrigatório',
            'produtos.*.quantidade.required_with' => 'O campo quantidade é obrigatório e tem que ser maior ou igual a zero (0)',
            'produtos.*.quantidade.gt' => 'O campo quantidade é obrigatório e tem que ser maior ou igual a zero (0)',
            'produtos.*.lote.required_if' => 'O campo lote é obrigatório quando o produto é do tipo OPME',
        ];
    }

    public function existsRule(Request $request, $table, $campo)
    {
        return Rule::exists($table, $campo)->where(function($query) use($request){
            $query->where('instituicao_id', $request->session()->get('instituicao'));
        });
    }
}
