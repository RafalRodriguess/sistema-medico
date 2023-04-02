<?php

namespace App\Http\Requests\comprador;

use Illuminate\Foundation\Http\FormRequest;

class CriarCompradorRequest extends FormRequest
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
            'email' => ['required'], 
            'usuario_id' => ['required'], 
            'ativo' => ['nullable'],
        ];
    }
}
