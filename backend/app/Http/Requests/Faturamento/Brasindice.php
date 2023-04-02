<?php

namespace App\Http\Requests\Faturamento;

use Illuminate\Foundation\Http\FormRequest;

class Brasindice extends FormRequest
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
            'tipo_id' => ['required'],
            'arquivo' => ['required', 'file', 'mimes:txt'],
            'edicao' => ['nullable', 'integer'],
            'vigencia' => ['nullable', 'date'],
        ];
    }
}
