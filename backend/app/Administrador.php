<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Administrador extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'administradores';

    protected $casts = [
        'developer' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'nome',
        'cpf',
        'email',
        'password',
        'perfis_usuario_id',
        'foto',
    ];

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = Hash::make($value);
    }

    public function perfil()
    {
        return $this->belongsTo(PerfilUsuario::class, 'perfis_usuario_id');
    }

    public function acoes()
    {
        return $this->morphMany(Log::class, "administrador");
    }

    public function habilidades()
    {
        return $this->belongsToMany(Habilidade::class, 'admin_administradores_has_habilidades', 'administrador_id', 'habilidade_id')
            ->withPivot('habilitado');
    }

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
}
