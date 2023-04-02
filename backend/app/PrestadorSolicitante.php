<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrestadorSolicitante extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'prestadores_solicitantes';

    protected $fillable = [
        'id',
        'instituicao_id',
        'nome',			
    ];

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search)){
            return $query;
        }

        if(preg_match('/^\d+$/', $search)){
            return $query->where('id','like', "{$search}%");
        }

        return $query->where('nome', 'like', "%{$search}%");
    }
}
