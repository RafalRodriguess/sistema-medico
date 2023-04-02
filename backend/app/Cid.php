<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Cid extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'cids';

    protected $fillable = [
        'id',
        'codigo',
        'descricao',
        'medico_id',
        'descricao_completa',
    ];

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search)){
            return $query;
        }

        if(preg_match('/^\d+$/', $search)){
            return $query->where('id','like', "{$search}%");
        }

        return $query->where('descricao', 'like', "%{$search}%");
    }
}