<?php

namespace App\Http\Requests\EstoqueInventario;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditarEstoqueInventarioRequest extends FormRequest
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

            'estoque_id' => ['required','exists:estoques,id'],
            'data' => ['required'],
            'hora' => ['required'],
            'aberta' => ['required'],
            'tipo_contagem' => ['required'],

        ];
    }
}
