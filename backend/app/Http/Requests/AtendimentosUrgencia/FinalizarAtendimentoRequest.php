<?php

namespace App\Http\Requests\AtendimentosUrgencia;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinalizarAtendimentoRequest extends FormRequest
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
        $instituicao = request()->session()->get('instituicao');
        return [
            'senhas_triagem_id' => [
                'numeric',
                'required'
            ],
            'especialidades_id' => [
                'numeric',
                'nullable'
            ],
            'id_prestador' => [
                'numeric',
                'required'
            ],
            'origens_id' => [
                'numeric',
                'required'
            ],
            'local_procedencia_id' => [
                'numeric',
                'nullable'
            ],
            'destino_id' => [
                'numeric',
                'nullable'
            ],
            'atendimentos_id' => [ // Procedência
                'numeric',
                'nullable'
            ],
            'cid' => [
                'nullable'
            ],
            'data' => [
                'nullable',
                'required_with:hora'
            ],
            'hora' => [
                'nullable',
                'required_with:data'
            ],
            'observacoes' => [
                'nullable'
            ],
            // Cadastro de pacientes
            'paciente_id' => [
                'numeric',
                'nullable',
                'required_without:cadastro-manual-paciente'
            ],
            'cadastro-manual-paciente' => [
                'nullable',
                'in:on'
            ],
            'paciente.nome' => [
                'required_with:cadastro-manual-paciente'
            ],
            'paciente.cpf' => [
                'required_with:cadastro-manual-paciente'
            ],
            'paciente.data_nascimento' => [
                'required_with:cadastro-manual-paciente'
            ],
            'paciente.email' => [
                'nullable'
            ],
            // Cadastro de carteirinhas
            'carteirinha_id' => [
                'nullable',
                'numeric'
            ],
            'cadastro-manual-carteirinha' => [
                'nullable',
                'in:on'
            ],
            'carteirinha.convenio_id' => [
                'required_with:cadastro-manual-carteirinha'
            ],
            'carteirinha.plano_id' => [
                'required_with:cadastro-manual-carteirinha'
            ],
            'carteirinha.carteirinha' => [
                'required_with:cadastro-manual-carteirinha'
            ],
            'carteirinha.validade' => [
                'required_with:cadastro-manual-carteirinha',
                'nullable',
                'date',
                'after:'.date('Y-m-d')
            ],
            // Cadastro de procedimentos,
            'procedimentos' => [
                'array',
                'nullable',
            ],
            'procedimentos.*.id_procedimento' => [
                'numeric',
                'required',
                Rule::exists('procedimentos_instituicoes', 'id')->where('instituicoes_id', $instituicao)
            ],
            'procedimentos.*.id_convenio' => [
                'numeric',
                'required',
                Rule::exists('convenios', 'id')->where('instituicao_id', $instituicao)
            ]
        ];
    }

    public function messages()
    {
        return [
            'carteirinha.*.required' => 'Campo obrigatório ao cadastrar uma carteirinha',
            'procedimentos.*' => 'Um ou mais procedimentos inválidos'
        ];
    }
}
