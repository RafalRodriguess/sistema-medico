<?php

namespace App;

use App\Support\ModelPossuiLogs;
use App\Support\ModelOverwrite;
use App\Instituicao;
use App\FilaTotem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Totem extends Model
{
	use SoftDeletes;
	use ModelPossuiLogs;
    use ModelOverwrite;

    protected $table = 'totens';

    protected $fillable = [
        'nome',
        'descricao',
        'instituicoes_id'
    ];

    protected $allowed_overwrite = [
        'App\FilaTotem'
    ];

    public function instituicao() {
        return $this->belongsTo(Instituicao::class, 'instituicoes_id');
    }

    public function filasTotem() {
        return $this->hasMany(FilaTotem::class, 'totens_id');
    }

    public function filasTriagem()
    {
        return $this->hasManyThrough(FilaTriagem::class, FilaTotem::class, 'totens_id', 'id', 'id', 'filas_triagem_id');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if (empty($search)) {
            return $query;
        }

        if (preg_match('/^\d+$/', $search)) {
            return $query->where('id', 'like', "{$search}%");
        }

        return $query->where('descricao', 'like', "%{$search}%")->orWhere('nome', 'like', "%{$search}%");
    }
}
