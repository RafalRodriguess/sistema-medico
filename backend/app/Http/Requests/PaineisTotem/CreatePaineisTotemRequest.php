<?php

namespace App\Http\Requests\PaineisTotem;

use App\Instituicao;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePaineisTotemRequest extends FormRequest
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
        $instituicao = Instituicao::find(request()->session()->get("instituicao"));
        $tipos_chamada = $instituicao->tiposChamadaTotem()->get()->pluck('id');
        $origens = $instituicao->origens()->get()->pluck('id');
        return [
            'origens_id' => ['required', Rule::in($origens)],
            'descricao' => ['required', 'string'],
            'opcoes' => ['nullable', 'array'],
            'opcoes.*.tipos_chamada_id' => ['required', Rule::in($tipos_chamada)],
            'opcoes.*.titulo' => ['required_with:opcoes.*.ativo'],
            'opcoes.*.local' => ['required_with:opcoes.*.ativo'],
            'opcoes.*.ativo' => ['nullable', 'in:on'],
        ];
    }
}
