<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgendaAusente extends Model
{
    use TraitLogInstituicao;
    use SoftDeletes;

    protected $table = 'instituicao_agendas_ausente';
    protected $fillable = [
        'id',
        'instituicao_id',
        'prestador_id',
        'data',
        'hora_inicio',
        'hora_fim',
        'dia_todo',
        'motivo'
    ];

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search))
        {
            return $query;
        }

        if(preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%");
        }

        return $query->where('nome', 'like', "%{$search}%");
    }

    public function prestadores(){
        return $this->hasMany(Prestador::class, 'id', 'prestador_id');
    }

    public function instituicoesPrestadores(){
        return $this->hasMany(InstituicoesPrestadores::class, 'prestadores_id', 'prestador_id');
    }
}
