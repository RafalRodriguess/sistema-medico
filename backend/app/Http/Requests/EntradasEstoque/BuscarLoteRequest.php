<?php

namespace App\Http\Requests\EntradasEstoque;

use Illuminate\Foundation\Http\FormRequest;

class BuscarLoteRequest extends FormRequest
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
            'search' => ['nullable'],
            'strict' => ['nullable', 'numeric'], // 1 ou null
            'id_entrada' => ['nullable', 'numeric']
        ];
    }
}
