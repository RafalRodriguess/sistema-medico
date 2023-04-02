<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstituicaoHabilidadeGrupo extends Model
{
    use SoftDeletes;

    protected $table = 'instituicao_habilidades_grupos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'nome',
        'categoria',
        'descricao',
    ];

    public function instituicaoHabilidades()
    {
        return $this->hasMany(InstituicaoHabilidade::class, 'habilidade_grupo_id');
    }
}
