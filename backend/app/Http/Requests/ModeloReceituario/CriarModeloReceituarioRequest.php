<?php

namespace App\Http\Requests\ModeloReceituario;

use Illuminate\Foundation\Http\FormRequest;

class CriarModeloReceituarioRequest extends FormRequest
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
            'tipo' => ['nullable'],
            'estrutura' => ['nullable'],
            'descricao' => ['required'],
            'texto' => ['nullable', 'required_with:estrutura'],
            'medicamentos.*.medicamento' => ['nullable', 'required_without:estrutura', 'exists:instituicao_medicamentos,id'],
            'medicamentos.*.quantidade' => ['nullable', 'required_without:estrutura'],
            'medicamentos.*.posologia' => ['nullable'],
            'composicoes.*.*.substancia' => ['nullable'],
            'composicoes.*.*.concentracao' => ['nullable'],
        ];
    }
}
