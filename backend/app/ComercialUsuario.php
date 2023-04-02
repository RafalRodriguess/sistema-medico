<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class ComercialUsuario extends Authenticatable
{
    //use Notifiable, SoftDeletes;
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'comercial_usuarios';

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
        'foto',
    ];

    /**
    * The relations to eager load on every query.
    *
    * @var array
    */

    protected $appends = ['foto_300px','foto_200px','foto_100px'];

    public function getFoto300pxAttribute()
    {
        if(is_null($this->foto) || empty($this->foto)){
            return null;
        }else{
            $caminho = Str::of($this->foto)->explode('/');

            return $caminho[0].'/'.$caminho[1].'/300px-'.$caminho[2];
        }
    }

    public function getFoto200pxAttribute()
    {
        if(is_null($this->foto) || empty($this->foto)){
            return null;
        }else{
            $caminho = Str::of($this->foto)->explode('/');

            return $caminho[0].'/'.$caminho[1].'/200px-'.$caminho[2];
        }
    }

    public function getFoto100pxAttribute()
    {
        if(is_null($this->foto) || empty($this->foto)){
            return null;
        }else{
            $caminho = Str::of($this->foto)->explode('/');

            return $caminho[0].'/'.$caminho[1].'/100px-'.$caminho[2];
        }
    }

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = Hash::make($value);
    }

    public function comercial()
    {
        return $this->belongsToMany(Comercial::class,'comercial_has_usuarios', 'usuario_id', 'comercial_id');
    }

    public function acoes()
    {
        return $this->morphMany(Log::class, "usuario");
    }

    public function comercialHabilidades()
    {
        return $this->belongsToMany(ComercialHabilidade::class, 'comercial_usuario_has_habilidades', 'usuario_id', 'habilidade_id')->withPivot('habilitado', 'comercial_id');
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
