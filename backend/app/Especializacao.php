<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;

class Especializacao extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'especializacoes';

    protected $fillable = [
        'descricao',
        'instituicoes_id'
    ];


    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search))
        {
            return $query->orderBy('id', 'desc');
        }

        if(preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%")->orderBy('id', 'desc');
        }

        return $query->where('nome', 'like', "%{$search}%")->orWhere('descricao', 'like', "%{$search}%")->orderBy('id', 'desc');
    }
}
