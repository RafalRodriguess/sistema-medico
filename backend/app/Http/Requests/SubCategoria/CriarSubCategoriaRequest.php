<?php

namespace App\Http\Requests\SubCategoria;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriarSubCategoriaRequest extends FormRequest
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
        // $comercialUsuario = $this->user('comercial');

        return [
            'nome' => ['required'],
            'categoria_id' => ['required',
                Rule::exists('categorias', 'id')
                    ->where('comercial_id', $this->session()->get('comercial'))
            ],
        ];
    }
}
