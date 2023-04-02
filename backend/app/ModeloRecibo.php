<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModeloRecibo extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = "modelos_recibo";

    protected $fillable = [
        'instituicao_id',
        'descricao',
        'texto',
        'vias',
    ];

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicao_id');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search)) return $query;

        if(preg_match('/^\d+$/', $search)) return $query->where('id','like', "{$search}%");

        return $query->where('descricao', 'like', "%{$search}%");
    }
}
