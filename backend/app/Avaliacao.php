<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Avaliacao extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = "avaliacoes";

    protected $fillable = [ 
        'paciente_id',
        'medico_id',
        'especialidade_id',
        'usuario_id',
        'avaliacao',
        'agendamento_id',
        'atendido'
    ];

    // protected $casts = [
    //     'atestado' => 'array',
    // ];

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

    public function prestador()
    {
        return $this->belongsTo(Prestador::class, 'medico_id');
    }

    public function especialidade()
    {
        return $this->belongsTo(Especialidade::class, 'especialidade_id');
    }
}
