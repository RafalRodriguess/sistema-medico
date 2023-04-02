<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\ModelOverwrite;

class EscalasPrestadores extends Model
{
    use ModelOverwrite;
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'escalas_prestadores';

    protected $fillable = [
        'entrada',
        'saida',
        'observacao',
        'prestador_id',
        'escala_medica_id'
    ];


}
