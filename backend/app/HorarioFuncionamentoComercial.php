<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HorarioFuncionamentoComercial extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'horarios_funcionamento_comerciais';

    protected $fillable = [
        'id',
        'dia_semana',
        'horario_inicio',
        'horario_fim',
        'full_time',
        'comercial_id',
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

    public function comercial()
    {
        return $this->belongsTo(Comercial::class, 'comercial_id');
    }
}
