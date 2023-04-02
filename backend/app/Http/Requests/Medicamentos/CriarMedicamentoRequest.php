<?php

namespace App\Http\Requests\Medicamentos;

use Illuminate\Foundation\Http\FormRequest;

class CriarMedicamentoRequest extends FormRequest
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
            'componente' => ['required'],
            'codigo_externo' => ['required']
        ];
    }
}
