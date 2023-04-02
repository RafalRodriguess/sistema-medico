<?php

namespace App\Http\Requests\Cirurgias;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Cirurgia;

class CriarCirurgiasRequest extends FormRequest
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
            'porte' => [
                'required',
                Rule::in(array_keys(Cirurgia::opcoes_porte))
            ],
            'obstetricia' => ['nullable', ],
            'tipo_parto_id' => ['nullable', 'required_if:obstetricia,1'],
            'previsao' => ['required'],
            'orientacoes' => ['nullable'],
            'preparos' => ['nullable'],
            'grupo_cirurgia_id' => ['required'],
            'tipo_anestesia_id' => ['required'],
            'procedimento_id' => ['nullable'],
            'convenio_id' => ['nullable'],
            'via_acesso_id' => ['required'],

            'equipamentos.*.equipamento_id' => ['nullable'],
            'equipamentos.*.quantidade' => ['nullable'],

            'especialidades.*.especialidade_id' => ['nullable'],

            'equipes.*.equipe_id' => ['nullable'],

            'salas.*.sala_id' => ['nullable'],
        ];
    }
}
