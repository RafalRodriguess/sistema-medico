<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConclusaoPaciente extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = "conclusoes_paciente";

    protected $fillable = [ 
        'paciente_id',
        'agendamento_id',
        'usuario_id',
        'conclusao',
        'motivo_conclusao_id',
        'compartilhado',
    ];

    protected $casts = [
        'conclusao' => 'array',
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

    public function motivo()
    {
        return $this->belongsTo(MotivoConclusao::class, 'motivo_conclusao_id')->withTrashed();
    }

    public function scopeGetTabela(Builder $query, $dados):Builder
    {
        $instituicao = request()->session()->get('instituicao');

        $query->whereBetween('created_at', [$dados['data_inicio']." 00:00:00", $dados['data_fim']." 23:59:59"])
        ->with(['paciente' => function($q) use($dados, $instituicao){
            $q->when($dados['paciente_id'], function($q1) use($dados){
                $q1->where('id', $dados['paciente_id']);
            });
            $q->where('instituicao_id', $instituicao);
        }, 'usuario' => function($q) use($dados){
            $q->when($dados['usuario_id'], function($q1) use($dados){
                $q1->whereIn('id', $dados['usuario_id']);
            });
            $q->with('prestadorMedico', 'prestadorMedico.prestador');
        }, 'motivo' => function($q) use($dados){
            $q->when($dados['motivo_conclusao_id'], function($q1) use($dados){
                $q1->whereIn('id', $dados['motivo_conclusao_id']);
            });
        }])
        ->whereHas('paciente', function($q) use($dados, $instituicao){
            $q->when($dados['paciente_id'], function($q1) use($dados){
                $q1->where('id', $dados['paciente_id']);
            });
            $q->where('instituicao_id', $instituicao);
        })
        ->whereHas('usuario', function($q) use($dados){
            $q->when($dados['usuario_id'], function($q1) use($dados){
                $q1->whereIn('id', $dados['usuario_id']);
            });
            $q->with('prestador', 'prestador.prestador');
        })
        ->whereHas('motivo', function($q) use($dados){
            $q->when($dados['motivo_conclusao_id'], function($q1) use($dados){
                $q1->whereIn('id', $dados['motivo_conclusao_id']);
            });
        });

        return $query;
    }
}
