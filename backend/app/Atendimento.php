<?php

namespace App;
use App\Support\ModelPossuiLogs;
use App\Instituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Atendimento extends Model
{
    use ModelPossuiLogs;
    use SoftDeletes;

	protected $table = 'atendimentos';

	protected $fillable = [
		'id',
		'nome',
        // 'descricao',
        'instituicao_id',
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

        return $query->where('descricao', 'like', "%{$search}%");
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'id', 'instituicao_id');
    }
}
