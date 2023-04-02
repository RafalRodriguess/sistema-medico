<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamePaciente extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = "exames_paciente";

    protected $fillable = [ 
        'paciente_id',
        'agendamento_id',
        'usuario_id',
        'exame',
        'compartilhado',
    ];

    protected $casts = [
        'exame' => 'array',
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
