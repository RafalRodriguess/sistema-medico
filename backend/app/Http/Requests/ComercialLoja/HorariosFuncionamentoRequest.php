<?php

namespace App\Http\Requests\ComercialLoja;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HorariosFuncionamentoRequest extends FormRequest
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
        // dd($this->request);
        return [
            'horario.id' => ['required'],
            'horario.id.*' => ['required'],
            'horario.horario_inicio' => ['nullable'],
            'horario.horario_inicio.*' => ['nullable'],
            'horario.horario_fim' => ['nullable'],
            'horario.horario_fim.*' => ['nullable'],
            'horario.full_time' => ['nullable'],
            'horario.full_time.*' => ['nullable'],
            'horario.fechado' => ['nullable'],
            'horario.fechado.*' => ['nullable'],
        ];
    }
    
}
