<?php

namespace App\Http\Requests\RegrasCobranca;

use Illuminate\Foundation\Http\FormRequest;

class CriarRegraCobrancaRequest extends FormRequest
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
            'descricao' => ['required'],
            'cir_mesma_via' => ['required'],
            'cir_via_diferente' => ['required'],
            'horario_especial' => ['nullable'],
            'base_via_acesso' => ['required'],
            'internacao' => ['nullable'],
            'ambulatorial' => ['nullable'],
            'urgencia_emergencia' => ['nullable'],
            'externo' => ['nullable'],
            'home_care' => ['nullable'],
        ];
    }
}
