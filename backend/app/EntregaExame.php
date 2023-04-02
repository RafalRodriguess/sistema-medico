<?php

namespace App;

use App\Support\ModelOverwrite;
use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Model;

class EntregaExame extends Model
{
    use ModelPossuiLogs;
    use ModelOverwrite;

    protected $table = "entregas_exame";

    protected $fillable = [
        'observacao',
        'status',
        'usuario_id',
        'local_entrega_id',
        'pessoa_id',
        'setor_exame_id'
    ];

    protected $allowed_overwrite = [
        EntregaExameProcedimento::class
    ];

    public const statuses = [
        1 => 'Pendente',
        2 => 'Falta resultado',
        3 => 'Falta receber',
        4 => 'Falta imprimir',
        5 => 'Falta entregar',
        6 => 'Entregue',
    ];

    public function instituicao()
    {
        return $this->hasOneThrough(Instituicao::class, LocalEntregaExame::class, 'id', 'id', 'local_entrega_id', 'instituicao_id');
    }

    public function localEntrega()
    {
        return $this->belongsTo(LocalEntregaExame::class, 'local_entrega_id');
    }

    public function Usuario()
    {
        return $this->belongsTo(InstituicaoUsuario::class, 'usuario_id');
    }

    public function entregasExameProcedimentos()
    {
        return $this->hasMany(EntregaExameProcedimento::class, 'entrega_exame_id');
    }

    public function procedimentosInstituicao()
    {
        return $this->hasManyThrough(InstituicaoProcedimentos::class, EntregaExameProcedimento::class, 'entrega_exame_id', 'id', 'id', 'procedimentos_instituicao_id');
    }

    public function procedimentos()
    {
        return $this->procedimentosInstituicao()->join('procedimentos', 'procedimentos.id', 'procedimentos_id')->select('procedimentos.*');
    }

    public function paciente()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }

    public function setorExame()
    {
        return $this->belongsTo(SetorExame::class, 'setor_exame_id');
    }
}
