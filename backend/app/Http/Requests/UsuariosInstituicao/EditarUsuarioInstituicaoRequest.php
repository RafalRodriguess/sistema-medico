<?php

namespace App\Http\Requests\UsuariosInstituicao;

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
            // 'cpf' => ['required', Rule::unique('instituicao_usuarios', 'cpf')->ignore($this->user('instituicao')->id)->whereNull('deleted_at'), 'cpf'],
            'cpf' => ['required', Rule::unique('instituicao_usuarios', 'cpf')->ignore($this->instituicao_usuario), 'cpf'],
            'password' => ['nullable'],
            'foto' => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
            'perfil_id' => ['required'],
            'desconto_maximo' => ['required', 'gte:0']
        ];
    }
}
