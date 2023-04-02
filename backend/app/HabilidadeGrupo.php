<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HabilidadeGrupo extends Model
{
    use SoftDeletes;

    protected $table = 'admin_habilidades_grupos';

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

    public function habilidades()
    {
        return $this->hasMany(Habilidade::class, "habilidade_grupo_id");
    }
}
