<?php

namespace App\Http\Requests\ModeloImpressao;

use Illuminate\Foundation\Http\FormRequest;

class CriarModeloImpressaoRequest extends FormRequest
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
            'tamanho_folha' => ['required'],
            'tamanho_fonte' => ['required'],
            'margem_cabecalho' => ['required'],
            'margem_direita' => ['required'],
            'margem_rodape' => ['required'],
            'margem_esquerda' => ['required'],
            'cabecalho' => ['nullable'],
            'rodape' => ['nullable'],
            'instituicao_prestador_id' => ['required', 'exists:instituicoes_prestadores,id'],
        ];
    }
}
