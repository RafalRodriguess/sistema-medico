<?php

namespace App\Http\Requests\NotasFiscais;

use Illuminate\Foundation\Http\FormRequest;

class CreateNotaFiscalRequest extends FormRequest
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
            // 'contas_receber_id' => ['required', 'exists:contas_receber,id'],
            'pessoa_id' => ['required', 'exists:pessoas,id'],
            'descricao' => ["required"],
            'valor_total' => ["required"],
            'deducoes' => ["nullable"],
            'observacoes' => ["nullable"],
            'contas_receber.*' => ['required', 'exists:contas_receber,id']
        ];
    }
}
