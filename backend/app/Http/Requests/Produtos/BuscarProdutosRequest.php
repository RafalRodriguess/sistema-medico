<?php

namespace App\Http\Requests\Produtos;

use Illuminate\Foundation\Http\FormRequest;

class BuscarProdutosRequest extends FormRequest
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
            'search' => ['string', 'nullable'],
            'id' => ['nullable', 'exists:produtos,id'],
            'ids' => ['nullable', 'array'],
            'ids.*' => ['required', 'exists:produtos,id'],
            'paginate' => ['nullable', 'in:1,true'],
            'limit' => ['nullable', 'numeric', 'max:50']
        ];
    }
}
