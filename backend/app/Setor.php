<?php

namespace App;

use App\Instituicao;
use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setor extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'setores';

    protected $fillable = [
        'id',
        'nome',
        'descricao',
        'instituicao_id',
    ];

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search)) return $query->orderBy('id', 'desc');

        if(preg_match('/^\d+$/', $search)) return $query->where('id','like', "{$search}%")->orderBy('id', 'desc');

        return $query->where('descricao', 'like', "%{$search}%")->orderBy('id', 'desc');
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'id', 'instituicao_id');
    }

}
