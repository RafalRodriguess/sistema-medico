<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntregaExameProcedimento extends Model
{
    protected $table = "entregas_exame_procedimentos";
    protected $fillable = [
        'entrega_exame_id',
        'procedimentos_instituicao_id',
    ];
    public $timestamps = false;

    public function entregaExame()
    {
        return $this->belongsTo(EntregaExame::class, 'entrega_exame_id');
    }

    public function procedimento()
    {
        return $this->hasOneThrough(Procedimento::class, InstituicaoProcedimentos::class, 'id', 'id', 'procedimentos_instituicao_id', 'procedimentos_id');
    }

    public function procedimentoInstituicao()
    {
        return $this->belongsTo(InstituicaoProcedimentos::class, 'procedimentos_instituicao_id');
    }
}
