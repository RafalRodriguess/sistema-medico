<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Support\TraitLogInstituicao;
use App\Instituicao;

class ProcessoTriagem extends Model
{
    use TraitLogInstituicao;

    protected $table = 'processos_triagem';
    protected $fillable = [
        'instituicoes_id',
        'descricao'
    ];

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicoes_id');
    }

    public function processosFilaTriagem()
    {
        return $this->hasMany(ProcessoFilaTriagem::class, 'processos_triagem_id');
    }

    public function filasTriagem()
    {
        return $this->hasManyThrough(FilaTriagem::class, ProcessoFilaTriagem::class, 'processos_triagem_id', 'id', 'id', 'filas_triagem_id');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if (empty($search)) {
            return $query;
        }

        if (preg_match('/^\d+$/', $search)) {
            return $query->where('id', 'like', "{$search}%");
        }

        return $query->where('descricao', 'like', "%{$search}%");
    }
}
