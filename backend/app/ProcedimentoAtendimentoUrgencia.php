<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcedimentoAtendimentoUrgencia extends Model
{
    protected $table = 'procedimento_atendimento_urgencia';
    protected $fillable = [
        'atendimento_urgencia_id',
        'id_procedimento',
        'id_convenio',
    ];
    public $timestamps = false;

    public function agendamentoAtendimentoUrgencia()
    {
        return $this->belongsTo(AgendamentoAtendimentoUrgencia::class, 'atendimento_urgencia_id');
    }

    public function instituicaoProcedimento()
    {
        return $this->belongsTo(InstituicaoProcedimentos::class, 'id_procedimento');
    }

    public function procedimento()
    {
        return $this->hasOneThrough(Procedimento::class, InstituicaoProcedimentos::class, 'id', 'id', 'id_procedimento', 'procedimentos_id');
    }

    public function convenio()
    {
        return $this->belongsTo(Convenio::class, 'id_convenio');
    }
}
