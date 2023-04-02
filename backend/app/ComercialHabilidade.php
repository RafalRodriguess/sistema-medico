<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComercialHabilidade extends Model
{
    use SoftDeletes;

    protected $table = 'comercial_habilidades';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome_unico',
        'nome',
        'descricao',
        'obrigatorio_grupo',
        'suporte_perfil',
        'sensivel',
        'habilidade_grupo_id',
    ];

    protected $cast = [
        'obrigatorio_grupo' => 'boolean',
        'suporte_perfil' => 'boolean',
        'sensivel' => 'boolean',
    ];

    public function comercialUsuarios()
    {
        return $this->belongsToMany(ComercialUsuario::class, 'comercial_usuario_has_habilidades', 'habilidade_id', 'usuario_id')->withPivot('habilitado', 'comercial_id');
    }
}
