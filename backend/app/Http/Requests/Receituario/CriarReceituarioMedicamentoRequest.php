<?php

namespace App\Http\Requests\Receituario;

use Illuminate\Foundation\Http\FormRequest;

class CriarReceituarioMedicamentoRequest extends FormRequest
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
            'medicamentos.*.medicamento' => ['required', 'exists:instituicao_medicamentos,id'],
            'medicamentos.*.quantidade' => ['required'],
            'medicamentos.*.posologia' => ['nullable'],
            'receituario_medicamento_id' => ['nullable', 'exists:receituarios_paciente,id'],
            'receituario_medicamento_tipo' => ['nullable'],
            'composicoes.*.*.substancia' => ['nullable'],
            'composicoes.*.*.concentracao' => ['nullable'],
            // 'compartilhado' => ['nullable']
        ];
    }
}
