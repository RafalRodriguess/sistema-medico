<?php

namespace App\Http\Requests\Comerciais;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditarComercialRequest extends FormRequest
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
            'cnpj' => ['required', Rule::unique('comerciais','cnpj')->ignore($this->comercial)->whereNull('deleted_at'), 'cnpj', 'formato_cnpj'],
            'razao_social' => ['required', Rule::unique('comerciais','razao_social')->ignore($this->comercial)->whereNull('deleted_at')],
            'categoria' => ['required', Rule::in(['drogaria','ortopedico'])],
            'telefone' => ['required'],
            'rua' => ['required'],
            'numero' => ['required'],
            'cep' => ['required'],
            'bairro' => ['required'],
            'cidade' => ['required'],
            'estado' => ['required'],
            'exibir' => ['nullable'],
            'complemento' => ['nullable'],
            'referencia' => ['nullable'],
            'cartao_credito' => ['nullable'],
            'cartao_entrega' => ['nullable'],
            'dinheiro' => ['nullable'],
            'imagem' => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
            'max_parcela' => ['required'],
            'free_parcela' => ['required'],
            'valor_parcela' => ['required'],
            'taxa_tectotum' => ['required'],
            'valor_minimo' => ['numeric','min:0'],
        ];
    }
}
