<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ramo extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;
    
    protected $table = "ramos";

    protected $fillable = [
        'id',
        'descricao',
    ];

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search)) return $query;

        if(preg_match('/^\d+$/', $search)) return $query->where('id','like', "{$search}%");

        return $query->where('descricao', 'like', "%{$search}%");
    }

    public function habilidades() {
        return $this->belongsToMany(InstituicaoHabilidade::class, 'ramos_habilidades', 'ramo_id', 'habilidade_id' );
    }
}
