<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcedimentoSus extends Model
{
    protected $table = "sus_tb_procedimento";
    protected $fillable = [
        'CO_PROCEDIMENTO',
        'NO_PROCEDIMENTO',
        'TP_COMPLEXIDADE',
        'TP_SEXO',
        'QT_MAXIMA_EXECUCAO',
        'QT_DIAS_PERMANENCIA',
        'QT_PONTOS',
        'VL_IDADE_MINIMA',
        'VL_IDADE_MAXIMA',
        'VL_SH',
        'VL_SA',
        'VL_SP',
        'CO_FINANCIAMENTO',
        'CO_RUBRICA',
        'QT_TEMPO_PERMANENCIA',
        'DT_COMPETENCIA',
        'instituicoes_id'
    ];
}
