<?php

namespace App\Http\Requests\Movimentacao;

use Illuminate\Foundation\Http\FormRequest;

class CriarMovimentacaoRequest extends FormRequest
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
            'tipo_movimentacao' => ['required'],
            'data' => ['required'],
            'conta_id_origem' => ['required', 'exists:contas,id'],
            'conta_id_destino' => ['required', 'exists:contas,id'],
            'valor' => ['required'],
            'obs' => ['nullable'],
        ];
    }
}
