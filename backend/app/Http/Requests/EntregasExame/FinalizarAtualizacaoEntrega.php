<?php

namespace App\Http\Requests\EntregasExame;

use App\EntregaExame;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinalizarAtualizacaoEntrega extends FormRequest
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
            'status' => ['required', Rule::in(array_keys(EntregaExame::statuses))],
            'observacao' => ['nullable']
        ];
    }
}
