<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EspecialidadeTriagem extends Model
{
    protected $table = "triagem_especialidades";
    protected $fillable = [
        'triagem_id',
        'especialidades_id'
    ];

    public $timestamps = false;
}
