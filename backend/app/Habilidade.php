<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Habilidade extends Model
{
    use SoftDeletes;

    protected $table = 'admin_habilidades';

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

    public function administradores()
    {
        return $this->belongsToMany(Administrador::class, 'admin_administradores_has_habilidades', 'habilidade_id', 'administrador_id')
            ->withPivot('habilitado');
    }

    public function perfis_usuario()
    {
        return $this->belongsToMany(Administrador::class, 'admin_perfis_usuario_has_habilidades', 'habilidade_id', 'perfis_usuario_id')
            ->withPivot('habilitado');
    }
}
