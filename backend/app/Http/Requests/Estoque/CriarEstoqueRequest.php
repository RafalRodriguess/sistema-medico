<?php

namespace App\Http\Requests\Estoque;

use Illuminate\Foundation\Http\FormRequest;

class CriarEstoqueRequest extends FormRequest
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
            'centro_custo_id' => ['required'],
        ];
    }
}
