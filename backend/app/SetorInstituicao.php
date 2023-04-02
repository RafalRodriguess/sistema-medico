<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SetorInstituicao extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'setores_instituicoes_sincronizacao';

    protected $fillable = [
        'id',
        'id_externo',
        'descricao',
        'instituicoes_id',
        'utiliza_agenda'
    ];
}
