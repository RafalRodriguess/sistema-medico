<?php

namespace App\Http\Requests\Marcas;

use Illuminate\Foundation\Http\FormRequest;

class CriarRequestMarca extends FormRequest
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
            'imagem' => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
        ];
    }
}
