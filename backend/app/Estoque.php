<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estoque extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'estoques';

    protected $fillable = [
        'id',
        'descricao',
        'tipo',
        'centro_custo_id',
        'instituicao_id'
    ];

    /**
     * Relação do estoque de destino de uma solicitação
     */
    public function solicitacoesEstoque()
    {
        return $this->hasMany(SolicitacaoEstoque::class, 'estoque_origem_id');
    }

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

        return $query->where('descricao', 'like', "%{$search}%");
    }
}
