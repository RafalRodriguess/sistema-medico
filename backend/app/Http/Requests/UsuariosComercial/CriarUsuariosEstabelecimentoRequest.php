<?php

namespace App\Http\Requests\UsuariosComercial;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class CriarUsuariosEstabelecimentoRequest extends FormRequest
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
            'usuario_id' => ['nullable', Rule::unique('comercial_has_usuarios', 'usuario_id')->where('comercial_id', $this->session()->get('comercial'))],
            'nome' => ['required_without_all:usuario_id'],
            'email' => ['required_without_all:usuario_id', 'email'],
            'cpf' => [
                'required_without_all:usuario_id',
                'cpf',
                tap(Rule::unique('comercial_usuarios', 'cpf'), function (Unique $rule) {
                    if ($this->has('usuario_id')) {
                        $rule->ignore($this->input('usuario_id'));
                    }
                }),
            ],
            'password' => ['required_without_all:usuario_id'],
            'imagem' => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
        ];

    }
}
