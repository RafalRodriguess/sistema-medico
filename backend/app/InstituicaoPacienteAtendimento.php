<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstituicaoPacienteAtendimento extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;
    
    protected $table = 'instituicao_has_pacientes_atendimentos';

    protected $fillable = [
        'id',
        'id_externo',
        'data',
        'tipo_atendimento',
        'nome_prestador',
        'nome_convenio',
        'especialidade_atendimento',
        'origem_atendimento',
        'anamnese_cid',
        'anamnese_descricao_cid',
        'anamnese_qp',
        'procedimento',
        'instituicao_has_pacientes_id'
    ];

    protected $casts = [
        "data" => "datetime",
    ];


}
