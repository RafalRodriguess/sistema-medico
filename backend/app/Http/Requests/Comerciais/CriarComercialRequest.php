<?php

namespace App\Http\Requests\Comerciais;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriarComercialRequest extends FormRequest
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
            'nome_fantasia' => ['required'],
            'email' => ['required', 'email'],
            'cnpj' => ['required', Rule::unique('comerciais','cnpj')->whereNull('deleted_at'), 'formato_cnpj'],
            'razao_social' => ['required', Rule::unique('comerciais','razao_social')->whereNull('deleted_at')],
            'categoria' => ['required', Rule::in(['drogaria','ortopedico'])],
            'telefone' => ['required'],
            'rua' => ['required'],
            'numero' => ['required'],
            'cep' => ['required'],
            'bairro' => ['required'],
            'cidade' => ['required'],
            'estado' => ['required'],
            'imagem' => ['required', 'file', 'mimes:jpeg,jpg,png'],
            'exibir' => ['nullable'],
            'complemento' => ['nullable'],
            'referencia' => ['nullable'],
            'cartao_credito' => ['nullable'],
            'cartao_entrega' => ['nullable'],
            'dinheiro' => ['nullable'],
            'max_parcela' => ['required'],
            'free_parcela' => ['required'],
            'valor_parcela' => ['required'],
            'taxa_tectotum' => ['required'],
            'valor_minimo' => ['numeric','min:0'],
        ];
    }
}
