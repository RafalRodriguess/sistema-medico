<?php

namespace App\Http\Requests\Receituario;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriarMedicamentoReceituarioRequest extends FormRequest
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
            'nome' => ['required'],
            'tipo' => ['required', Rule::in(['industrializado','manipulado'])],
            'concentracao' => ['nullable'],
            'forma_farmaceutica' => ['nullable'],
            'via_administracao' => ['required'],
            'composicoes.*.substancia' => ['nullable', 'required_if:tipo,manipulado'],
            'composicoes.*.concentracao' => ['nullable', 'required_if:tipo,manipulado'],
            'quantidade' => ['nullable'],
            'posologia' => ['nullable']
        ];
    }
}
