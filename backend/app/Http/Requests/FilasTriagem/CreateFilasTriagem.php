<?php

namespace App\Http\Requests\FilasTriagem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Instituicao;

class CreateFilasTriagem extends FormRequest
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
        // Validar se está em uso baseado nas filas da instituicao autual
        $instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        $origens = $instituicao->origens()->get()->pluck('id')->toArray();
        return [
            'origens_id' => [
                'integer',
                'required',
                Rule::in($origens)
            ],
            'descricao' => [
                'string',
                'required'
            ],
            'identificador' => [
                'alpha',
                'required',
                'max:2',
                Rule::unique('filas_triagem')->where(function ($query) use ($instituicao) {
                    return $query->where('instituicoes_id', $instituicao->id);
                })
            ],
            'ativo' => [
                'in:on',
                'nullable'
            ],
            'prioridade' => [
                'in:on',
                'nullable'
            ],
            'processos' => [
                'array',
                'nullable'
            ],
            'processos.*.ordem' => [
                'required',
                'integer'
            ],
            'processos.*.processos_triagem_id' => [
                'required',
                'integer',
                Rule::exists('processos_triagem','id')->where(function ($query) use ($instituicao) {
                    return $query->where('instituicoes_id','=',$instituicao->id);
                })
            ]
        ];
    }

    public function messages()
    {
        return [
            'identificador.unique' => 'O identificador escolhido já foi selecionado por outra fila',
            'processos.*.ordem.*' => 'Uma ou mais ordens foram inseridas incorretamente',
            'processos.*.processos_triagem_id.required' => 'Uma ou mais triagens escolhida(s) são inválidas',
            'processos.*.processos_triagem_id.integer' => 'Uma ou mais triagens escolhida(s) são inválidas',
            'processos.*.processos_triagem_id.exists' => 'Você não tem permissão de utilizar uma ou mais dos processos escolhido(s)',
        ];
    }
}
