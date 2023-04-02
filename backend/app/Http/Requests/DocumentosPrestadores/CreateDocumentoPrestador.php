<?php

namespace App\Http\Requests\DocumentosPrestadores;

use Illuminate\Foundation\Http\FormRequest;

class CreateDocumentoPrestador extends FormRequest
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
            'documentos' => [
                'nullable',
                'exclude_without:documentos',
                'array',
            ],
            'documentos.*.tipo' => [
                'nullable',
                'required_with:documentos',
                'integer'
            ],
            'documentos.*.descricao' => [
                'nullable',
                'required_with:documentos',
                'string'
            ],
            'documentos.*.arquivo' => [
                'nullable',
                'required_with:documentos',
                'file',
                'mimes:png,jpg,jpeg,csv,txt,xlx,xls,pdf',
                'max:2048'
            ],
        ];
    }

    public function messages()
    {
        return [
            'documentos.*.tipo.required_with' => 'O Tipo do documento é obrigatório',
            'documentos.*.descricao.required_with' => 'A Descrição do documento é obrigatória',
            'documentos.*.arquivo.required_with' => 'O Arquivo do documento é obrigatório'
        ];
    }
}
