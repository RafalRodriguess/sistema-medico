<?php

namespace App;


use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\ModelOverwrite;

class Especialidade extends Model
{
    use ModelOverwrite;
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'especialidades';

    protected $fillable = [
        'id',
        'descricao',
    ];

    protected $allowed_overwrite = [
        'App\\EspecializacaoEspecialidade'
    ];

    public function countPrestadoresInstituicoes(){

        // $this->belongsToMany(InstituicoesPrestadores::class, 'inst_prest_especialidades', 'especialidade')
        return InstituicoesPrestadores::query()->whereHas('especialidades', function($q) {
            $q->where('especialidade_id', $this->id);
        })->count();
        // return $this->hasMany(InstituicoesPrestadores::class, 'especialidades_id');
    }

    public function prestadoresInstituicao(){
        return $this->hasMany(InstituicoesPrestadores::class, 'especialidade_id');
    }

    public function prestador()
    {
        return $this->belongsToMany(Prestador::class, 'instituicoes_prestadores', 'especialidade_id', 'prestadores_id')->whereNull('instituicoes_prestadores.deleted_at');
    }

    public function prestadoresInstituicoes()
    {
        return $this->belongsToMany(InstituicoesPrestadores::class, 'inst_prest_especialidades', 'especialidade_id', 'instituicao_prestador_id');
    }

    public function instituicoes()
    {
        return $this->belongsToMany(Instituicao::class, 'instituicoes_prestadores', 'especialidade_id', 'instituicoes_id')->whereNull('instituicoes_prestadores.deleted_at');
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

    public function especializacoes()
    {
        return $this->hasManyThrough(Especializacao::class, EspecializacaoEspecialidade::class,'especialidades_id','id','id','especializacoes_id');
    }

    public function especializacoesEspecialidade()
    {
        return $this->hasMany(EspecializacaoEspecialidade::class,'especialidades_id');
    }
}
