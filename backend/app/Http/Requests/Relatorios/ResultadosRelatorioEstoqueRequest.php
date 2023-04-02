<?php

namespace App\Http\Requests\Relatorios;

use App\Instituicao;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResultadosRelatorioEstoqueRequest extends FormRequest
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
        $instituicao = Instituicao::find(request()->session()->get('instituicao'));

        return [
            'estoques' => ['nullable', 'array'],
            'estoques.*' => [
                'required',
                'numeric',
                Rule::exists('estoques', 'id')->where('instituicao_id', $instituicao->id)
            ],
            'produtos' => ['nullable', 'array'],
            'produtos.*' => [
                'required',
                'numeric',
                Rule::exists('produtos', 'id')->where('instituicao_id', $instituicao->id)
            ],
            'centros_custos' => ['nullable', 'array'],
            'centros_custos.*' => [
                'required',
                'numeric',
                Rule::exists('centros_de_custos', 'id')->where('instituicao_id', $instituicao->id)
            ],
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
            'destino-saida-estoque' => [
                'nullable',
                'in:0,1,2'
            ]
        ];
    }
}
