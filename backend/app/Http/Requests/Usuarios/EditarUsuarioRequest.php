<?php

namespace App\Http\Requests\Usuarios;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EditarUsuarioRequest extends FormRequest
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
            'nome' => ['required'],
            'telefone' => ['required'],
            'cpf' => ['required', Rule::unique('usuarios', 'cpf')->ignore($this->usuario), 'cpf'],
            'data_nascimento' => ['required', 'date', 'before:now'],
            // 'nome_mae' => ['nullable'],
            // 'data_nascimento_mae' => ['nullable', 'date', 'before:data_nascimento'],
            'email' => ['nullable', 'email'],
            // 'imagem' => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
        ];
    }
}
