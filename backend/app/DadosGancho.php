<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DadosGancho extends Model
{
    protected $table = 'ganchos_dados_instituicao';
    protected $fillable = [
        'ganchos_id',
        'instituicoes_id',
        'model',
        'dados',
    ];
    public $timestamps = false;

    protected $casts = [
        'dados' => 'json'
    ];
}
