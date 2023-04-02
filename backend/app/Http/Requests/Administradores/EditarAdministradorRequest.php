<?php

namespace App\Http\Requests\Administradores;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditarAdministradorRequest extends FormRequest
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
            'cpf' => ['required', Rule::unique('administradores', 'cpf')->ignore($this->administrador), 'cpf'],
            'password' => ['nullable'],
            'perfis_usuario_id' => ['required', 'exists:perfis_usuario,id']
        ];
    }
}
