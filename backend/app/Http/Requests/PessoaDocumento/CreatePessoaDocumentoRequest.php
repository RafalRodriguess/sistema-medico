<?php

namespace App\Http\Requests\PessoaDocumento;

use Illuminate\Foundation\Http\FormRequest;

class CreatePessoaDocumentoRequest extends FormRequest
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
                'integer'
            ],
            'descricao' => [
                'required',
                'string'
            ],
            'arquivo' => [
                'required',
                'file',
                'mimes:png,jpg,jpeg,csv,txt,xlx,xls,pdf',
                'max:2048'
            ],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Campo obrigatório',
        ];
    }
}
