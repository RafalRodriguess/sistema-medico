<?php

namespace App\Http\Requests\Convenios;

use Illuminate\Foundation\Http\FormRequest;

class VincularPrestadorRequest extends FormRequest
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
            'convenio_id' => ['required', 'exists:convenios,id'],
            'prestadores' => ['required'],
            'prestadores.*' => ['required', 'exists:instituicoes_prestadores,id'],
        ];
    }
}
