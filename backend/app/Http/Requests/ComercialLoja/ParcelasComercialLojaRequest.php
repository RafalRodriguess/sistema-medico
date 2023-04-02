<?php

namespace App\Http\Requests\ComercialLoja;

use Illuminate\Foundation\Http\FormRequest;

class ParcelasComercialLojaRequest extends FormRequest
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
            'max_parcela' => ['numeric','gt:1'],
            'free_parcela' => ['numeric','gt:1','max:max_parcela'],
            'valor_parcela' => ['numeric','min:0','max:100'],
            'valor_minimo' => ['numeric','min:0'],
        ];
    }
}
