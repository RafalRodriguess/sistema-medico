<?php

namespace App\Http\Requests\UsuarioEnderecos;

use Illuminate\Foundation\Http\FormRequest;

class CriarUsuarioEnderecoRequest extends FormRequest
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
            'rua' =>['required'],
            'numero' =>['required'],
            'cep' =>['required'],
            'bairro' =>['required'],
            'cidade' =>['required'],
            'estado' =>['required'],
            'referencia' =>['nullable'],
            'complemento' =>['nullable'],
            'usuario_id' =>['required', 'exists:usuarios,id'],
        ];
    }
}
