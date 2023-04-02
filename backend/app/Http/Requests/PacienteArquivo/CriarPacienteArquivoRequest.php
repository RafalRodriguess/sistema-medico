<?php

namespace App\Http\Requests\PacienteArquivo;

use Illuminate\Foundation\Http\FormRequest;

class CriarPacienteArquivoRequest extends FormRequest
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
            'nome_pasta' => ['required'],
            'nome_arquivo' => ['required'],
            'arquivo_upload' => ['required'],
            'arquivo_upload.*' => ['required', 'file']
        ];
    }
}
