<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AtualizarPerfilRequest extends FormRequest
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
            'nome' => ['required', 'string', 'max:255'],
            'telefone' => ['required', 'min:14'],
            'email' => ['required', 'string', 'email:rfc,dns'],
            'password' => ['nullable','string', 'min:3'],
            'cpf' => ['required', 'string', Rule::unique('usuarios', 'cpf')->ignore($this->id), 'cpf'],
            'imagem' => ['nullable','file', 'mimes:jpeg,jpg,png'],
        ];
    }

}
