<?php

namespace App\Http\Requests\FaturamentoSUS;

use Illuminate\Foundation\Http\FormRequest;

class UploadFaturamentoRequest extends FormRequest
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
            'arquivo' => ['file', 'required']
        ];
    }
}
