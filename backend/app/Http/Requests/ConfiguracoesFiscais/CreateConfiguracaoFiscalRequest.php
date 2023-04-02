<?php

namespace App\Http\Requests\ConfiguracoesFiscais;

use App\ConfiguracaoFiscal As ConfiguracaoFiscal;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateConfiguracaoFiscalRequest extends FormRequest
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
            'descricao' => ['required'],
            'aliquota_iss' => ['nullable'],
            'iss_retido_fonte' => ['nullable'],
            'cnae' => ['nullable'],
            'cod_servico_municipal' => ['required'],
            'p_pis' => ['nullable'],
            'p_cofins' => ['nullable'],
            'p_inss' => ['nullable'],
            'p_ir' => ['nullable'],
            'regime' => ['required', Rule::in(ConfiguracaoFiscal::regime())],
            // 'regime' => ['nullable'],
            'item_lista_servicos' => ['nullable'],
            'usuario' => ['nullable'],
            'senha' => ['nullable'],
            'certificado' => ['nullable', 'file'],
            'senha_certificado' => ['nullable', 'required_with:certificado'],
            'ambiente' => ['required'],
            'regime_especial_tributacao' => ['nullable'],
        ];
    }
}
