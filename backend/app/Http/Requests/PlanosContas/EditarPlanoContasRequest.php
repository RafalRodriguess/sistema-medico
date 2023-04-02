<?php

namespace App\Http\Requests\PlanosContas;

use Illuminate\Foundation\Http\FormRequest;

class EditarPlanoContasRequest extends FormRequest
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
            'rateio_auto' => ['required'],
            'cc.*.centro_custos_id' => ['nullable', 'exists:centros_de_custos,id'],
            'cc.*.percentual' => ['nullable']
        ];
    }
}
