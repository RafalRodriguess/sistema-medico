<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class InstituicaoUsuario extends Authenticatable
{
    //use Notifiable, SoftDeletes;
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'instituicao_usuarios';

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

    public function instituicao()
    {
        return $this->belongsToMany(Instituicao::class,'instituicao_has_usuarios', 'usuario_id', 'instituicao_id')->wherePivot('status', '=', 1)->where('habilitado', true)->withPivot(['status', 'perfil_id', 'visualizar_prestador', 'visualizar_setores', 'desconto_maximo']);
    }

    public function instituicaoTrashed()
    {
        return $this->belongsToMany(Instituicao::class,'instituicao_has_usuarios', 'usuario_id', 'instituicao_id')->where('habilitado', true)->withPivot(['status', 'perfil_id', 'visualizar_prestador', 'visualizar_setores', 'desconto_maximo']);
    }

    public function contas()
    {
        return $this->belongsToMany(Conta::class, 'contas_usuarios', 'usuario_id', 'conta_id');
    }

    public function contasInstituicao()
    {
        return $this->belongsToMany(Conta::class, 'contas_usuarios', 'usuario_id', 'conta_id')->where('instituicao_id', request()->session()->get('instituicao'));
    }

    public function acoes()
    {
        return $this->morphMany(Log::class, "usuario");
    }

    public function instituicaoHabilidades()
    {
        return $this->belongsToMany(InstituicaoHabilidade::class, 'instituicao_usuario_has_habilidades', 'usuario_id', 'habilidade_id')->withPivot('habilitado', 'instituicao_id');
    }

    public function prestador()
    {
        $instituicao_id = Session::get('instituicao');
        return $this->hasMany(InstituicoesPrestadores::class, 'instituicao_usuario_id')->where('instituicoes_id', $instituicao_id);
    }

    public function prestadorMedico()
    {
        $instituicao_id = Session::get('instituicao');
        return $this->hasMany(InstituicoesPrestadores::class, 'instituicao_usuario_id')->where('instituicoes_id', $instituicao_id)->where('tipo', 2);
    }

    public function contatos()
    {
        return $this->hasMany(ChatContato::class, 'usuario_origem');
    }

    public function contatosAlheios()
    {
        return $this->hasMany(ChatContato::class, 'usuario_contato');
    }

    public function medicamentos()
    {
        $this->belongsToMany(InstituicaoMedicamento::class, 'medicamentos_add_prestador', 'instituicao_usuario_id', 'instituicao_medicamento_id')->withPivot('quantidade', 'posologia');
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

        return $query->where('nome', 'like', "%{$search}%")->orderBy('id', 'desc');
    }
}
