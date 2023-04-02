<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerfilUsuario extends Model
{

    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'perfis_usuario';

    protected $fillable = [
        'id',
        'nome_valido',
        'descricao',
    ];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'perfis_usuario_id');
    }

    public function habilidades()
    {
        return $this->belongsToMany(Habilidade::class, 'admin_perfis_usuario_has_habilidades', 'perfis_usuario_id', 'habilidade_id')
            ->withPivot('habilitado');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search))
        {
            return $query->orderBy('id', 'desc');
        }

        if(preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%")->orderBy('id', 'desc');
        }

        return $query->where('descricao', 'like', "%{$search}%")->orderBy('id', 'desc');
    }
}
