<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegiaoProcedimento extends Model
{
    protected $table = 'regiao_procedimentos';

    protected $fillable = [
        'id',
        'descricao',
        'tipo_limpeza',
    ];

    protected $casts = [
        'tipo_limpeza' => 'boolean',
    ];
}
