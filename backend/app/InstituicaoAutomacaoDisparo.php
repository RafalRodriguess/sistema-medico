<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstituicaoAutomacaoDisparo extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'instituicoes_automacoes_disparos';

    protected $fillable = [
        'id',
        'instituicao_id',
        'data_execucao',
        'modulo',
        'regra'
    ];



}
