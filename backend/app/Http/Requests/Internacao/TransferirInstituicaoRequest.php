<?php

namespace App\Http\Requests\Internacao;

use Illuminate\Foundation\Http\FormRequest;

class TransferirInstituicaoRequest extends FormRequest
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
            'instituicao_transferencia_id' => ['required'],
            'data_transferencia' => ['required'],
            'obs_transferencia' => ['nullable'],
        ];
    }
}
