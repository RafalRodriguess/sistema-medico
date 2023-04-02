<?php

namespace App\Http\Requests\Faturamento;

use Illuminate\Foundation\Http\FormRequest;

class CriarProcedimentoFaturamentoRequest extends FormRequest
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
            'proc.*.data_vigencia' => ['required'],
            'proc.*.procedimento_id' => ['required'],
            'proc.*.descricao' => ['required'],
            'proc.*.vl_honorario' => ['required'],
            'proc.*.vl_operacao' => ['required'],
            'proc.*.vl_total' => ['required'],
            'proc.*.ativo' => ['nullable'],
        ];
    }
}
