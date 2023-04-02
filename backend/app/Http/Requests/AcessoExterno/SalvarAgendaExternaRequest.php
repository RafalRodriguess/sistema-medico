<?php

namespace App\Http\Requests\AcessoExterno;

use Illuminate\Foundation\Http\FormRequest;

class SalvarAgendaExternaRequest extends FormRequest
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
            'instituicao' => ['required'],
            'codigo_acesso_terceiros' => ['required'],
            "instituicao_prestador_id" => ['required'],
            "especialidade_id" => ['required'],
            "convenio_id" => ['required'],
            "procedimentos_instituicoes_convenios" => ['required'],
            "instituicoes_agenda" => ['required'],
            "data" => ['required'],
            'paciente' => ['required'],
            'data_nascimento' => ['required'],
            'telefone' => ['required'],
            'id_externo' => ['nullable'],
        ];
    }
}
