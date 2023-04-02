<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaudoPaciente extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = "laudos_paciente";

    protected $fillable = [ 
        'paciente_id',
        'agendamento_id',
        'usuario_id',
        'laudo',
        'compartilhado',
    ];

    protected $casts = [
        'laudo' => 'array',
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
