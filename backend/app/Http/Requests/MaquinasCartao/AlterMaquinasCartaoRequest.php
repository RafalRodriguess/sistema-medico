<?php

namespace App\Http\Requests\MaquinasCartao;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AlterMaquinasCartaoRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'descricao' => ['required'],
            'codigo' => [
                'required',
                Rule::unique('maquinas_cartoes', 'codigo')->where('instituicao_id', $request->session()->get('instituicao'))->whereNull('deleted_at')->ignore($this->maquina)
            ],
            'taxa_debito' => ['nullable', 'numeric', 'min:0','max:100'],
            'taxa_credito.*' => ['nullable', 'numeric', 'min:0','max:100'],
            'dias_parcela_credito' => ['nullable', 'numeric', 'min:0','max:30'],
            'dias_parcela_debito' => ['nullable', 'numeric', 'min:0','max:30'],
        ];
    }
}
