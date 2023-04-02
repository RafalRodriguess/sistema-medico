<?php

namespace App\Http\Requests\ModeloConclusao;

use Illuminate\Foundation\Http\FormRequest;

class CriarModeloConclusaoRequest extends FormRequest
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
            'instituicao_prestador_id' => ['required', 'exists:instituicoes_prestadores,id'],
            'descricao' => ['required'],
            'motivo_conclusao_id' => ['required', 'exists:motivos_conclusoes,id'],
            'texto' => ['required']
        ];
    }
}
