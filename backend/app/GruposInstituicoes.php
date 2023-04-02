<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GruposInstituicoes extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'grupos_instituicoes';

    protected $fillable = [
        'id',
    ];

    public function grupo()
    {
        return $this->belongsTo(GruposProcedimentos::class, 'grupo_id','id');
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicao_id','id');
    }

    public function agenda(){
        return $this->hasMany(InstituicoesAgenda::class, 'grupos_instituicoes_id');
    }





}
