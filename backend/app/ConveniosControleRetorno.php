<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConveniosControleRetorno extends Model
{
    protected $table = 'convenios_controle_retorno';
    protected $fillable = [
        'convenios_id',
        'grupo',
        'campo',
    ];
    public $timestamps = false;

    const opcoes_campos_retorno = [
        0 => 'Prestador',
        1 => 'Procedimento',
        2 => 'Especialidade',
        3 => 'Serviço',
        4 => 'Livre',
        5 => 'CID',
    ];

    const tipos_grupos_atendimento = [
        'retorno_atendimento_ambulatorio' => 0,
        'retorno_atendimento_externo' => 1,
        'retorno_atendimento_urgencia' => 2,
    ];
}
