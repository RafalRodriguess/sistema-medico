<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Model;

class VinculoSUS extends Model
{
    use ModelPossuiLogs;

    protected $table = "vinculos_sus";
    protected $fillable = [
        'id_procedimento',
        'id_sus',
        'id_instituicao'
    ];
}
