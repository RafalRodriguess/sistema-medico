<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstPrestEspecialidade extends Model
{
    protected $table = 'inst_prest_especialidades';

    protected $fillable = [
        'instituicao_prestador_id',
        'especialidade_id',
    ];

    public $timestamps = false;
}
