<?php

namespace App\Http\Requests\AgendamentoCentroCirurgico;

use Illuminate\Foundation\Http\FormRequest;

class AgendarCentroCirurgicoRequest extends FormRequest
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
            'centro_cirurgico_novo_id' => ['required', 'exists:centros_cirurgicos,id'],
            'sala_cirurgica_novo' => ['nullable', 'required_without:interditar_novo', 'exists:salas_cirurgicas,id'],
            'data_novo' => ['required'],
            'hora_inicio_novo' => ['required'],
            'cirurgia_novo' => ['nullable', 'required_without:interditar_novo', 'exists:cirurgias,id'],
            'cirurgiao_novo' => ['nullable', 'required_without:interditar_novo', 'exists:prestadores,id'],
            'hora_final_novo' => ['required_if:interditar_novo,1'],
            'interditar_novo' => ['nullable'],
        ];
    }

    public function messages()
    {
        return [
            'centro_cirurgico_novo_id.required' => 'o campo centro cirúrgico é requerido!',
            'sala_cirurgica_novo.required_without' => 'o campo sala cirúrgica é requerido!',
            'data_novo.required' => 'o campo data é requerido!',
            'hora_inicio_novo.required' => 'o campo hora inicio é requerido!',
            'cirurgia_novo.required_without' => 'o campo cirurgia é requerido!',
            'cirurgiao_novo.required_without' => 'o campo cirurgião é requerido!',
        ];
    }
}
