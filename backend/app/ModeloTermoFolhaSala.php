<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModeloTermoFolhaSala extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = "modelo_termos";

    protected $fillable = [
        'id',
        'nome',
        'descricao',
        'tipo',
        'instituicao_id',
    ];

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search)) return $query;

        if(preg_match('/^\d+$/', $search)) return $query->where('id','like', "{$search}%");

        $query->where('nome', 'like', "%{$search}%");

        return $query;
    }
}
