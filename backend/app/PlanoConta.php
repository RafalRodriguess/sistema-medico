<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanoConta extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'planos_contas';

    protected $fillable = [
        'id',
        'descricao',
        'codigo',
        'plano_conta_id',
        'padrao',
        'rateio_auto',
        'centro_custo_id',
        'rateio',
        'instituicao_id'
    ];

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        $query->orderBy('codigo', 'asc');

        if(empty($search))
        {
            return $query;
        }

        if(preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%");
        }

        return $query->where('descricao', 'like', "%{$search}%");
    }

    public function scopeGetTotalPai(Builder $query, int $instituicao): Builder
    {
       return $query->whereNull('plano_conta_id')->where('instituicao_id', $instituicao);
    }

    public function scopeGetTotalFilhos(Builder $query, int $plano_conta_id): Builder
    {
       return $query->where('plano_conta_id', $plano_conta_id);
    }

    public function scopeGetPai(Builder $query, int $plano_conta_id): Builder
    {
       return $query
            // ->whereNull('plano_conta_id')
            ->where('id',  $plano_conta_id)
            ->select('descricao', 'codigo');
    }

    public function centroCusto()
    {
        return $this->belongsToMany(CentroCusto::class, 'rateio_auto_plano_conta', 'plano_conta_id', 'centro_custos_id')->withPivot('percentual');
    }

    public function scopeGetReceitas(Builder $query): Builder
    {
        return $query->where('padrao', 0)->orderBy('codigo', 'asc');
    }

    public function scopeGetDespesas(Builder $query): Builder
    {
        return $query->where('padrao', 1)->orderBy('codigo', 'asc');
    }
}
