<?php

namespace App\Http\Requests\ComercialFretes\Entrega;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class UpdateFretesEntregasRequest extends FormRequest
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
            'ativado' =>['nullable'],
            'tipo_prazo' => ['required'],
            'prazo_minimo' => ['required'],
            'prazo_maximo' => ['required'],
        ];

    }
}
