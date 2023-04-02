<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RamoHabilidade extends Model
{
    protected $table = "ramos_habilidades";

    protected $fillable = [
        'habilidade_id',
    ];

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search)) return $query;

        if(preg_match('/^\d+$/', $search)) return $query->where('ramo_id','like', "{$search}%");

        return $query->where('descricao', 'like', "%{$search}%");
    }

    public function habilidades() {
        return $this->hasMany(InstituicaoHabilidade::class, 'habilidade_id');
    }
}
