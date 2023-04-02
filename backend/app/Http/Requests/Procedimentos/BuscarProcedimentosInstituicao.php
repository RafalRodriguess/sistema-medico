<?php

namespace App\Http\Requests\Procedimentos;

use App\Instituicao;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BuscarProcedimentosInstituicao extends FormRequest
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
        $instituicao = Instituicao::find(request()->session()->get('instituicao'));
        return [
            'search' => ['nullable'],
            'convenio_id' => ['required', Rule::exists('convenios', 'id')->where('instituicao_id', $instituicao->id)]
        ];
    }
}
