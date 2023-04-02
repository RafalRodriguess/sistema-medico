<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConvenioInstituicao extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'convenios_instituicoes_sincronizacao';

    protected $fillable = [
        'id',
        'id_externo',
        'convenios_id',
        'instituicoes_id',
    ];

    public function convenio()
    {
        return $this->belongsTo(Convenio::class, 'convenios_id', 'id');
    }

}
