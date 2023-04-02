<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModalidadeExame extends Model
{
	use SoftDeletes;
	use ModelPossuiLogs;

	protected $table = 'modalidades_exame';

	protected $fillable = [
		'id',
        'instituicao_id',
        'sigla',
		'descricao',
	];

    public function instituicao() {
        return $this->belongsTo(Instituicao::class, 'instituicao_id');
    }

    public function exames() {
        return $this->hasMany(InstituicaoProcedimentos::class, 'modalidades_exame_id');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if (empty($search)) {
            return $query->orderBy('id', 'desc');
        }

        if (preg_match('/^\d+$/', $search)) {
            return $query->where('id', 'like', "{$search}%")->orderBy('id', 'desc');
        }

        return $query->where('descricao', 'like', "%{$search}%")->orWhere('sigla', 'like', "%{$search}%")->orderBy('id', 'desc');
    }
}
