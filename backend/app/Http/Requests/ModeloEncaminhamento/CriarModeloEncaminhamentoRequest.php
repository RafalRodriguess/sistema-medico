<?php

namespace App\Http\Requests\ModeloEncaminhamento;

use Illuminate\Foundation\Http\FormRequest;

class CriarModeloEncaminhamentoRequest extends FormRequest
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
            'texto' => ['required']
        ];
    }
}
