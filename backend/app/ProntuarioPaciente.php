<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProntuarioPaciente extends Model
{
    use TraitLogInstituicao;
    use SoftDeletes;

    protected $table = "prontuarios_paciente";

    protected $fillable = [
        'paciente_id',
        'agendamento_id',
        'usuario_id',
        'prontuario',
        'compartilhado',
    ];

    protected $casts = [
        'prontuario' => 'array',
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

    public function cids()
    {
        return $this->belongsToMany(Cid::class, 'prontuario_has_cid', 'prontuario_paciente_id', 'cid_id');
    }
}
