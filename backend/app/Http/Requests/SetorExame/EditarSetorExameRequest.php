<?php

namespace App\Http\Requests\SetorExame;

use Illuminate\Foundation\Http\FormRequest;

class EditarSetorExameRequest extends FormRequest
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
            'tipo' => ['required'],
        ];
    }
}
