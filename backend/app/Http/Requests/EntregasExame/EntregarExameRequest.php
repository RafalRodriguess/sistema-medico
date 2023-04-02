<?php

namespace App\Http\Requests\EntregasExame;

use App\EntregaExame;
use App\Instituicao;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EntregarExameRequest extends FormRequest
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
            'pessoa_id' => [
                'numeric',
                'nullable',
                Rule::exists('pessoas', 'id')->where('instituicao_id', $instituicao)
            ],
            'setor_exame_id' => [
                'required',
                Rule::exists('setores_exame', 'id')->where('instituicao_id', $instituicao)
            ],
            'local_entrega_id' => [
                'required', Rule::exists('locais_entrega_exame', 'id')->where('instituicao_id', $instituicao)
            ],
            'status' => [
                'required', Rule::in(array_keys(EntregaExame::statuses))
            ],
            'observacao' => [
                'nullable'
            ],
            'procedimentos' => [
                'array',
                'required',
                'min:1'
            ],
            'procedimentos.*' => [
                'numeric',
                'required',
                Rule::exists('procedimentos_instituicoes', 'id')->where('instituicoes_id', $instituicao)
            ]
        ];
    }
}
