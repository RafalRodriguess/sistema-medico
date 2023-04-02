<?php

namespace App\Http\Requests\ChatApp;

use Illuminate\Foundation\Http\FormRequest;

class BuscarNotificacoesRequest extends FormRequest
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
            'ultima_mensagem' => ['date', 'nullable']
        ];
    }
}
