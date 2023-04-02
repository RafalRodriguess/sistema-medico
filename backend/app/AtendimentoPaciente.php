<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AtendimentoPaciente extends Model
{
    use TraitLogInstituicao;
    use SoftDeletes;

    protected $table = 'atendimentos_paciente';

    protected $fillable = [
        'id',
        'instituicao_id',
        'usuario_atendeu',
        'motivo_atendimento_id',
        'paciente_id',
        'agendamento_id',
        'descricao',
    ];

    public function motivo()
    {
        return $this->belongsTo(MotivoAtendimento::class, 'motivo_atendimento_id');
    }
    
    public function motivoView()
    {
        return $this->belongsTo(MotivoAtendimento::class, 'motivo_atendimento_id')->withTrashed();
    }

    public function paciente()
    {
        return $this->belongsTo(Pessoa::class, 'paciente_id');
    }
    
    public function usuario()
    {
        return $this->belongsTo(InstituicaoUsuario::class, 'usuario_atendeu');
    }

    public function scopeSearch(Builder $query, string $search = '', $paciente_id, string $usuario = ""): Builder
    {
        $query->where('paciente_id', $paciente_id);

        if(!empty($usuario)){
            $query->where('usuario_atendeu', $usuario);
        }

        if(empty($search)){
            return $query;
        }

        return $query->where('motivo_atendimento_id', $search);
    }
}
