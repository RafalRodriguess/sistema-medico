<?php

namespace App\Http\Requests\PlanosContas;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class CriarPlanoContasRequest extends FormRequest
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
            'codigo' => ['required', Rule::unique('planos_contas', 'codigo')->where('instituicao_id', $request->session()->get('instituicao'))->whereNull('deleted_at')],
            'padrao' => ['required'],
            'rateio_auto' => ['required'],
            'plano_conta_id' => ['nullable'],
            // 'centro_custo_id' => ['nullable', 'required_if:rateio_auto,1', 'exists:centros_de_custos,id'],
            //'rateio' => ['required_if:rateio_auto,1'],
            'cc.*.centro_custos_id' => ['nullable', 'exists:centros_de_custos,id'],
            'cc.*.percentual' => ['nullable']
        ];
    }
}
