<?php

namespace App\Http\Requests;

use App\Instituicao;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VincularProcedimentoSusRequest extends FormRequest
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
        $instituicao = request()->session()->get('instituicao');
        return [
            'id_procedimento' => [
                Rule::exists('procedimentos_instituicoes', 'procedimentos_id')->where('instituicoes_id', $instituicao)
            ],
            'id_sus' => [
                'nullable',
                Rule::exists('sus_tb_procedimento', 'id')->where('instituicoes_id', $instituicao)
            ]
        ];
    }
}
