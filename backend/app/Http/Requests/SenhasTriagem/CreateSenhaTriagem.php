<?php

namespace App\Http\Requests\SenhasTriagem;

use App\Instituicao;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSenhaTriagem extends FormRequest
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
        $instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        $filas_totem = [];
        collect($instituicao->totens()->get())->map(function($item) use (&$filas_totem) {
            $filas_totem = array_merge($filas_totem, $item->filasTotem()->get()->pluck('id')->toArray());
        });
        return [
            'filas_totem_id' => [
                'required',
                'integer',
                Rule::in($filas_totem)
            ]
        ];
    }

    public function messages()
    {
        return [
            'filas_totem_id.required' => 'Escolha uma fila',
            'filas_totem_id.integer' => 'Escolha uma fila válida',
            'filas_totem_id.in' => 'Você não tem permissão de entrar nessa fila'
        ];
    }
}
