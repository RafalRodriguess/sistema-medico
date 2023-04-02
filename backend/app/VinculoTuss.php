<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VinculoTuss extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = "vinculo_tuss";

    protected $fillable = [
        'terminologia_id',
        'instituicao_id',
        'cod_termo',
        'termo',
        'descricao_detalhada',
        'data_vigencia',
        'data_vigencia_fim',
        'data_implantacao_fim',
        'cod_terminologia',
        'forma_envio',
        'cod_grupo',
        'descricao_grupo',
    ];

    public function terminologia()
    {
        return $this->belongsTo(VinculoTussTerminologia::class, 'terminologia_id');
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'id', 'instituicao_id');
    }

    public function scopeSearch(Builder $query, string $search = ""): Builder
    {
        $query->where('cod_termo', 'like', "{$search}%");
        $query->orWhere('termo', 'like', "%{$search}%");

        return $query;
    }
}
