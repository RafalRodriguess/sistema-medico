<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Instituicao;

class ClassificacaoTriagem extends Model
{
    use ModelPossuiLogs;
    use SoftDeletes;

    protected $table = 'classificacoes_triagem';
    protected $fillable = [
        'instituicoes_id',
        'descricao',
        'cor',
        'prioridade'
    ];

    // Reduz o tamanho ocupado no bd ao salvar sem o #
    public function setCorAttribute($value)
    {
        $this->attributes['cor'] = str_replace('#', '', $value);
    }

    // Retorna com o # por questão de praticidade
    public function getCorAttribute()
    {
        return '#'.$this->attributes['cor'];
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicoes_id');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if (empty($search)) {
            return $query;
        }

        if (preg_match('/^\d+$/', $search)) {
            return $query->where('id', 'like', "{$search}%");
        }

        return $query->where('descricao', 'like', "%{$search}%")->whereNull('deleted_at');
    }
}
