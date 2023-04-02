<?php

namespace App\Http\Requests\ChatApp;

use Illuminate\Foundation\Http\FormRequest;

class BuscarContatosRequest extends FormRequest
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
            'busca' => ['string', 'nullable'],
            'ultimos_contatos' => ['array', 'nullable'],
            'ultimos_contatos.*' => ['required'],
            'ignorar_ordenacoes' => ['nullable'],
            'exibir_ultima_mensagem' => ['nullable'],
            'contato_recente' => ['nullable', 'numeric']
        ];
    }
}
