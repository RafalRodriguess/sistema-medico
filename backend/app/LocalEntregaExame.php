<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Model;

class LocalEntregaExame extends Model
{
    use ModelPossuiLogs;

    protected $table = "locais_entrega_exame";
    protected $fillable = [
        'descricao',
        'instituicao_id',
    ];

    public $timestamps = false;
}
