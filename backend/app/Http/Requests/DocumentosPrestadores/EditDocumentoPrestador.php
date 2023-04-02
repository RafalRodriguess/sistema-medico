<?php

namespace App\Http\Requests\DocumentosPrestadores;

use Illuminate\Foundation\Http\FormRequest;

class EditDocumentoPrestador extends FormRequest
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
            'tipo' => [
                'required',
                'integer',
            ],
            'descricao' => [
                'required',
                'string',
            ],
        ];
    }

    public function messges()
    {
        return [
            'tipo.required' => 'O Tipo do documento é obrigatório',
            'descricao.required' => 'A Descrição do documento é obrigatória'
        ];
    }
}
