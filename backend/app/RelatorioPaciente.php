<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RelatorioPaciente extends Model
{
    use TraitLogInstituicao;
    use SoftDeletes;

    protected $table = "relatorios_paciente";

    protected $fillable = [
        'paciente_id',
        'agendamento_id',
        'usuario_id',
        'relatorio',
        'compartilhado',
    ];

    protected $casts = [
        'relatorio' => 'array',
    ];

    public function paciente()
    {
        return $this->belongsTo(Pessoa::class, 'paciente_id');
    }

    public function agendamento()
    {
        return $this->belongsTo(Agendamentos::class, 'agendamento_id');
    }

    public function usuario()
    {
        return $this->belongsTo(InstituicaoUsuario::class, 'usuario_id');
    }
}
