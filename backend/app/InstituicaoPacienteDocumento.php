<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstituicaoPacienteDocumento extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;
    
    protected $table = 'instituicao_has_pacientes_documentos';

    protected $fillable = [
        'id',
    ];


}
