<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\ModelPossuiLogs;
use App\Support\TraitLogInstituicao;

class ApresentacaoConvenio extends Model
{
    use TraitLogInstituicao;
    use SoftDeletes;

    protected $table = 'apresentacoes_convenio';
    protected $fillable = [
        'nome',
        'instituicao_id'
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
}
