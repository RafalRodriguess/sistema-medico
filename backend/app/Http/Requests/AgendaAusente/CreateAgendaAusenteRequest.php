<?php

namespace App\Http\Requests\AgendaAusente;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateAgendaAusenteRequest extends FormRequest
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
            'data' => ['required'],
            'dia_todo' => ['nullable'],
            'hora_inicio' => [
                'nullable',
                Rule::requiredIf(function(){
                    return  request()->dia_todo == null ;
                })
            ],
            'hora_fim' => [
                'nullable',
                Rule::requiredIf(function(){
                    return  request()->dia_todo == null ;
                })
            ],
            'motivo' => ['nullable'],
            'prestador_id' => ['required'],
            'repetir' => ['nullable'],
            'repetir_data' => ['nullable', 'required_if:repetir,1'],
        ];
    }
}
