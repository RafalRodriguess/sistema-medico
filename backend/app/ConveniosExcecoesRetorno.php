<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConveniosExcecoesRetorno extends Model
{
    protected $table = 'convenios_excecoes_retorno';
    protected $fillable = [
        'convenios_id',
        'procedimentos_id'
    ];
    public $timestamps = false;

    public function procedimento()
    {
        return $this->belongsTo(Procedimento::class, 'procedimentos_id');
    }
}
