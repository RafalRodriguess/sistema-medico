<?php

namespace App\Http\Requests\Prestadores;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditarPrestadorRequest extends FormRequest
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
            // 'nome' => ['required'],
            // 'crm' => ['required', Rule::unique('prestadores', 'crm')->ignore($this->prestadore)],
            // 'cpf' => ['required', Rule::unique('prestadores', 'cpf')->ignore($this->prestadore), 'cpf', 'formato_cpf'],
            'especialidade' => ['required'],
        ];
    }
}
