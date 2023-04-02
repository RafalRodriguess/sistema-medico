<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SetorExame extends Model
{
	use SoftDeletes;
	use ModelPossuiLogs;

    protected $table = 'setores_exame';

    protected $fillable = [
        'descricao',
        'tipo',
        'ativo',
        'instituicao_id'
    ];

    const tipos = [
        'anatomia',
        'banco de sangue',
        'diagnóstico por imagem',
        'laboratório SADT'
    ];

    public function setTipoAttribute($value) {
        $this->attributes['tipo'] = in_array($value, self::tipos) ? $value : null;
    }

    public function centrosCusto() {
        return $this->hasMany(CentroCusto::class, 'setor_exame_id');
    }

    public function instituicao() {
        return $this->belongsTo(Instituicao::class, 'instituicao_id');
    }

    public function prestadores() {
        return $this->hasManyThrough(Prestador::class, 'setores_prestadores_exame', 'prestadores_id', 'setores_exame_id');
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
