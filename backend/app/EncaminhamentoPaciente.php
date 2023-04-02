<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EncaminhamentoPaciente extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = "encaminhamentos_paciente";

    protected $fillable = [ 
        'paciente_id',
        'agendamento_id',
        'usuario_id',
        'encaminhamento',
        'compartilhado',
    ];

    protected $casts = [
        'encaminhamento' => 'array',
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
