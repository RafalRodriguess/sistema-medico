<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerfilUsuarioInstituicao extends Model
{
    use ModelPossuiLogs;
    use SoftDeletes;
    

    protected $table = 'perfis_usuarios_instituicoes';

    protected $fillable = [
        'id',
        'nome',
    ];

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search))
        {
            return $query;
        }

        if(preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%");
        }

        return $query->where('nome', 'like', "%{$search}%");
    }

    // public function usuarios()
    // {
    //     return $this->hasMany(Usuario::class, 'perfis_usuario_id');
    // }

    public function habilidades()
    {
        return $this->belongsToMany(InstituicaoHabilidade::class, 'perfis_usuarios_instituicoes_habilidades', 'perfil_id', 'habilidade_id');
    }
}
