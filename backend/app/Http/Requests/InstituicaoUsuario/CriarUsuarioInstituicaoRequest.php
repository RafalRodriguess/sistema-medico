<?php

namespace App\Http\Requests\InstituicaoUsuario;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class CriarUsuarioInstituicaoRequest extends FormRequest
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
            'usuario_id' => ['nullable', Rule::unique('instituicao_has_usuarios', 'usuario_id')->where('instituicao_id', $this->instituicao->id)],
            'nome' => ['required_without_all:usuario_id'],
            'email' => ['required_without_all:usuario_id', 'email'],
            'cpf' => [
                'required_without_all:usuario_id',
                'cpf',
                tap(Rule::unique('instituicao_usuarios', 'cpf'), function (Unique $rule) {
                    if ($this->has('usuario_id')) {
                        $rule->ignore($this->input('usuario_id'));
                    }
                }),
            ],
            'password' => ['required_without_all:usuario_id'],
            'imagem' => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
            'perfil_id' => ['required'],
        ];

    }
}
