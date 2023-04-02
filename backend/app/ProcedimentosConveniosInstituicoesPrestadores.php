<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ProcedimentosConveniosInstituicoesPrestadores extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'procedimentos_convenios_instituicoes_prestadores';

    protected $fillable = [
        'id',
        'instituicoes_prestadores_id',
        'procedimentos_convenios_id',
        'procedimentos_id',
    ];


    public function prestadores()
    {
        return $this->belongsTo(InstituicoesPrestadores::class, 'instituicoes_prestadores_id');
    }

    public function procedimentos_convenios()
    {
        return $this->belongsTo(ConveniosProcedimentos::class, 'procedimentos_convenios_id');
    }

    public function procedimentos()
    {
        return $this->belongsTo(Procedimento::class, 'procedimentos_id');
    }

}
