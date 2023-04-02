<?php

namespace App\Http\Requests\InstituicaoAgenda;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\Types\Nullable;

class EditarInstituicaoAgendaRequest extends FormRequest
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
            'unicos' => [],
            'checkbox.*.*.*' => 'integer',
            'inicio.*.*.*' => 'date_format:H:i',
            'intervalo.*.*.*' => 'date_format:H:i',
            'duracao.*.*.*' => 'date_format:H:i',
            'termino.*.*.*' => 'date_format:H:i',
            'atendimento.*.*.*' => 'date_format:H:i',
            'setor_id_agenda.*.*.*' => ['required','exists:setores_exame,id'],
            'convenio_id.*.*.*.*'=>['required','exists:convenios,id'],
            'faixa_etaria_agenda.*.*.*' => ['required', Rule::in(['todas', 'menor_12', 'acima_12', 'acima_60'])],
            'continue' => ['nullable'],
            'obs' => ['nullable'],
        ];
    }

    public function messages(){
        return [
            'inicio.*.*.*.date_format' => 'Horário de início incorreto',
            'intervalo.*.*.*.date_format' => 'Horário de intervalo incorreto',
            'duracao.*.*.*.date_format' => 'Duração do intervalo incorreto',
            'termino.*.*.*.date_format' => 'Horário de termino incorreto',
            'atendimento.*.*.*.date_format' => 'Duração de atendimento incorreto',
            'setor_id_agenda.*.*.*.required' => 'Setor incorreto',
            'faixa_etaria_agenda.*.*.*.required' => 'Faixa etária incorreto',
        ];
    }
}
