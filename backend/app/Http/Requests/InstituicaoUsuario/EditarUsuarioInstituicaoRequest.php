<?php

namespace App\Http\Requests\InstituicaoUsuario;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditarUsuarioInstituicaoRequest extends FormRequest
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
            'email' => ['required', 'email'],
            'cpf' => ['required', Rule::unique('instituicao_usuarios', 'cpf')->ignore($this->instituicao_usuario), 'cpf'],
            'password' => ['nullable'],
            'imagem' => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
            'perfil_id' => ['required'],
        ];
    }
}
