<?php

namespace App\Http\Requests\AcessoExterno;

use Illuminate\Foundation\Http\FormRequest;

class AcessoExternoRequest extends FormRequest
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
            'instituicao' => ['required', 'exists:instituicoes,id'],
            'codigo_acesso_terceiros' => ['required'],
            'dia_semana' => ['nullable'],
            'dia_mes' => ['nullable'],
            'especialidade' => ['nullable'],
            'data' => ['nullable'],
            'prestador' => ['nullable'],
            'procedimento' => ['nullable'],
        ];
    }
}
