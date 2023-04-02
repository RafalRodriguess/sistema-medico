<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'usuarios';

    protected $fillable = [
        'id',
        'nome',
        'data_nascimento',
        'cpf',
        'telefone',
        'cod',
        'password',
        'nome_mae',
        'data_nascimento_mae',
        'email',
        'customer_id',
        'fcm_token',
        'convenio_id',
        'imagem',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'data_nascimento_mae' => 'date',
    ];

    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = Hash::make($value);
    }

    public function usuarioEnderecos()
    {
        return $this->hasMany(UsuarioEndereco::class, 'usuario_id');
    }

    public function usuarioCartoes()
    {
        return $this->hasMany(UsuarioCartao::class, 'usuario_id');
    }

    public function acoes()
    {
        return $this->morphMany(Log::class, "usuario");
    }

    public function instituicao()
    {
        return $this->belongsToMany(Instituicao::class, 'instituicao_has_pacientes', 'usuario_id', 'instituicao_id')
            ->using(InstituicaoPaciente::class)
            ->withPivot('metadados', 'id_externo', 'id');
    }

    public function prontuarios()
    {
        return $this->hasManyThrough(Prontuario::class, InstituicaoPaciente::class, 'usuario_id', 'instituicao_has_pacientes_id', 'id',  'id');
    }

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }

    public function getCpfNumerosAttribute()
    {
        return preg_replace('/[^\d]+/', '', $this->cpf);
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
