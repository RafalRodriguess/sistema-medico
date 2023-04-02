<?php

namespace App\Http\Requests\ProcedimentosInstituicao;

use Illuminate\Foundation\Http\FormRequest;

class CriarProcedimentoInstituicaoRequest extends FormRequest
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
            'procedimentos_id' => ['required', 'exists:procedimentos,id'],
            'grupo_id' => ['required'],
            'tipo' => ['required','in:avulso,ambos,unico'],
            'modalidades_exame_id' => ['numeric', 'exists:modalidades_exame,id', 'nullable']
        ];
    }
}
