<?php

namespace App\Http\Requests\ProdutoPerguntas;

use Illuminate\Foundation\Http\FormRequest;

class CriarProdutoPerguntaRequest extends FormRequest
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
            'titulo' => ['required'],
            'obrigatorio' => ['nullable'],
            'tipo' => ['required'],

            'alternativa' => ['required_unless:tipo,Texto','array'],
            'alternativa.*' => [
                $this['tipo'] == 'Texto' ? 'nullable' : 'required',
                'string',
            ],
            
            'preco' => ['nullable', 'array'],
            'preco.*' => [
                'nullable','numeric'
            ],

            'quantidade_maxima' => ['nullable', 'numeric'],
            'quantidade_minima' => ['nullable', 'numeric'],

            'quantidade_maxima_itens' => ['nullable', 'array'],
            'quantidade_maxima_itens.*' => [
                'nullable','numeric'
            ]
        ];
    }
}
