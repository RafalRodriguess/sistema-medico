<?php

namespace App\Http\Requests\Especializacoes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class CriarEspecializacaoRequest extends FormRequest
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
            // 'descricao' => ['string', 'required', 'unique:especializacoes,descricao,instituicoes_id'],
            'descricao' => ['string', 'required', Rule::unique('especializacoes', 'descricao')->where('instituicoes_id', $request->session()->get('instituicao'))->whereNull('deleted_at')],
        ];
    }
}
