<?php

namespace App\Http\Requests\ClassificacoesTriagem;

use Illuminate\Foundation\Http\FormRequest;

class CreateClassificacaoTriagem extends FormRequest
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
            'descricao' => ['string', 'required'],
            'cor' => ['string', 'required', 'max:7'],
            'prioridade' => ['numeric', 'required', 'min:0', 'max:100']
        ];
    }
}
