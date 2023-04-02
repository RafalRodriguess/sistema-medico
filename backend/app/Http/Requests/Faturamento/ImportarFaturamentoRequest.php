<?php

namespace App\Http\Requests\Faturamento;

use Illuminate\Foundation\Http\FormRequest;

class ImportarFaturamentoRequest extends FormRequest
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
            'arquivo' => ['required', 'file', 'mimetypes:text/csv,text/plain'],
            'terminologia_id' => ['required']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'arquivo.mimetypes' => 'Formato de arquivo não suportado, somente arquivos .csv são suportados!'
        ];
    }
}
