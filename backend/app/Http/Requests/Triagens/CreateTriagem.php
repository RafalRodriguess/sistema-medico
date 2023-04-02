<?php

namespace App\Http\Requests\Triagens;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Instituicao;

class CreateTriagem extends FormRequest
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
            'classificacoes_triagem_id' => [
                'integer',
                'required',
            ],
            'queixa' => [
                'required'
            ],
            'sinais_vitais' => [
                'required'
            ],
            'doencas_cronicas' => [
                'nullable'
            ],
            'alergias' => [
                'nullable'
            ],
            'pessoa_id' => [
                'numeric',
                'nullable',
                Rule::exists('pessoas', 'id')->where('instituicao_id', $instituicao)
            ],
            'paciente_nome' => [
                'min:3',
                'nullable',
                'required_without:paciente_cadastrado'
            ],
            'paciente_mae' => [
                'nullable'
            ],
            'paciente_cpf' => [
                'nullable',
                'unique:pessoas,cpf'
            ],
            'paciente_cadastrado' => [
                'nullable',
                'in:on'
            ],
            'primeiro_atendimento' => [
                'nullable',
                'in:on'
            ],
            'reincidencia' => [
                'nullable',
                'in:on'
            ],
            'prestador_id' => [
                'nullable',
                Rule::exists('instituicoes_prestadores', 'prestadores_id')->where('instituicoes_id', $instituicao)
            ],
            'especialidades' => [
                'nullable',
                'array'
            ],
            'especialidades.*' => [
                Rule::exists('instituicoes_prestadores', 'especialidade_id')->where('instituicoes_id', $instituicao)
            ]
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'O campo :attribute é obrigatório.',
            'classificacoes_triagem_id.*' => 'Escolha uma classificação válida.',
            'nome_paciente.required_without' => 'O nome do paciente é obrigatório.',
            'pessoa_id.required_with' => 'O paciente é obrigatório, escolha um paciente ou cadastre um novo.',
            'cpf.unique' => 'O CPF informado já está cadastrado no sistema.',
        ];
    }
}
