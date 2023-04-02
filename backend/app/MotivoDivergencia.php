<?php

namespace App;

use App\Support\ModelPossuiLogs;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MotivoDivergencia extends Model
{
    use ModelPossuiLogs;

    protected $table = 'motivos_divergencia';
    protected $fillable = [
        'instituicoes_id',
        'descricao'
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

        $instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        return $query->where('instituicoes_id', '=', $instituicao->id)->where('descricao', 'like', "%{$search}%");
    }
}
