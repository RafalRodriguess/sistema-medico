<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComercialHabilidadeGrupo extends Model
{
    use SoftDeletes;

    protected $table = 'comercial_habilidades_grupos';

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

    public function comercialHabilidades()
    {
        return $this->hasMany(ComercialHabilidade::class, 'habilidade_grupo_id');
    }
}
