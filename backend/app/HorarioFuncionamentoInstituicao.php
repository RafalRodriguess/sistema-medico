<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HorarioFuncionamentoInstituicao extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'horario_atendimento_instituicao';

    protected $fillable = [
        'id',
        'dia_semana',
        'horario_inicio',
        'horario_fim',
        'full_time',
        'instituicao_id',
        'fechado',
    ];

    protected $cast = [
        'full_time' => 'boolean',
        'horario_inicio' => 'time',
        'horario_fim' => 'time',
        'fechado' => 'boolean',
    ];

    public static function ConvertDiaSemana($dia){
        $retorno = [
            'segunda-feira' => 'Segunda',
            'terça-feira' => 'Terça',
            'quarta-feira' => 'Quarta',
            'quinta-feira' => 'Quinta',
            'sexta-feira' => 'Sexta',
            'sabado' => 'Sabado',
            'domingo' => 'Domingo',
        ];

        return $retorno[$dia];
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicao_id');
    }
}
