<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AtestadoPaciente extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = "atestados_paciente";

    protected $fillable = [ 
        'paciente_id',
        'agendamento_id',
        'usuario_id',
        'atestado',
        'compartilhado',
    ];

    protected $casts = [
        'atestado' => 'array',
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
