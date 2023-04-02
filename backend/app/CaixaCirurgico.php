<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaixaCirurgico extends Model
{
    use TraitLogInstituicao;
    use SoftDeletes;

    protected $table = 'caixas_cirurgicos';

    protected $fillable = [
        'descricao',
        'descricao_resumida',
        'qtd',
        'tempo_esterelizar',
        'ativo',
        'instituicao_id'
    ];

    protected $casts = [
        'ativo' => 'boolean',
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

        return $query->where(function($q) use($search){
            $q->where('descricao', 'like', "%{$search}%");
            $q->orWhere('descricao_resumida', 'like', "%{$search}%");
        });
    }
}
