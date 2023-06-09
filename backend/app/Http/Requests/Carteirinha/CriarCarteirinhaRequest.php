<?php

namespace App\Http\Requests\Carteirinha;

use Illuminate\Foundation\Http\FormRequest;

class CriarCarteirinhaRequest extends FormRequest
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
            'pessoa_id' => ['required'],
            'convenio_id' => ['required'],
            'plano_id' => ['required'],
            'carteirinha' => ['required'],
            'validade' => ['required'],

          
        ];
    }
}
