<?php

namespace App\Http\Requests\ContaBancaria;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriarContaBancariaRequest extends FormRequest
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
            'banco_nome' => ['required','max:255'],
            'banco_id' => ['required', 'digits:3'],
            'agencia' => ['required','digits_between:1,5','numeric'],
            'agencia_dv' => ['max:1'],
            'conta' => ['required','digits_between:5,13','numeric'],
            'conta_dv' => ['required','max:2'],
            'type' => ['required','in:conta_corrente,conta_corrente_conjunta,conta_poupanca,conta_poupanca_conjunta'],
            'documento_titular' => ['required','digits_between:11,16','numeric'],
            'nome_titular' => ['required','max:30'],
        ];
    }
}
