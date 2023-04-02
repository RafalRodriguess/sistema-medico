<?php

namespace App\Http\Requests\Especialidades;

use Illuminate\Foundation\Http\FormRequest;

class EditarEspecialidadeRequest extends FormRequest
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
            'descricao' => ['nullable', 'string', 'unique:especialidades,descricao,'.request()->route('especialidade')->id],
            // 'especializacoes' => ['nullable', 'array'],
            // 'especializacoes.*.especializacoes_id' => ['required','exists:especializacoes,id']
        ];
    }

    public function messages()
    {
        return [
            'descricao.required' => 'A Descrição da especialidade é obrigatório',
            'descricao.string' => 'A Descrição da especialidade deve ser um texto',
            'descricao.unique' => 'Especialidade já registrada',
            'especializacoes.*.especializacoes_id.*' => 'Uma ou mais especializacoes são inválidas'
        ];
    }
}
