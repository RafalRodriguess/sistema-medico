<?php

namespace App\Http\Requests\Receituario;

use Illuminate\Foundation\Http\FormRequest;

class CriarReceituarioLivreRequest extends FormRequest
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
            'receituario_livre_id' => ['nullable', 'exists:receituarios_paciente,id'],
            'receituario_livre' => ['required'],
            'receituario_livre_tipo' => ['nullable'],
            'compartilhado' => ['nullable']
        ];
    }
}
