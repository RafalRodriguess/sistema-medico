<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PerfilInstituicaoHabilidade extends Model
{
    protected $table = "perfis_usuarios_instituicoes_habilidades";

    protected $fillable = [
        'habilidade_id',
    ];

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search)) return $query;

        if(preg_match('/^\d+$/', $search)) return $query->where('perfil_id','like', "{$search}%");

        return $query->where('descricao', 'like', "%{$search}%");
    }

    public function habilidades() {
        return $this->hasMany(InstituicaoHabilidade::class, 'habilidade_id');
    }
}
