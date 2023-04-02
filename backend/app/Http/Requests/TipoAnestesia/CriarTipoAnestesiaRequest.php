<?php

namespace App\Http\Requests\TipoAnestesia;

use Illuminate\Foundation\Http\FormRequest;

class CriarTipoAnestesiaRequest extends FormRequest
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
            'descricao' => ['required'],
            'cobranca_aih' => ['required']
        ];
    }
}
