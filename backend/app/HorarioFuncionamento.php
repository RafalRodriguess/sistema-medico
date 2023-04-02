<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HorarioFuncionamento extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'horarios_funcionamento';

    protected $fillable = [
        'id',
        'segunda_feira_inicio',
        'segunda_feira_fim',
        'terca_feira_inicio',
        'terca_feira_fim',
        'quarta_feira_inicio',
        'quarta_feira_fim',
        'quinta_feira_inicio',
        'quinta_feira_fim',
        'sexta_feira_inicio',
        'sexta_feira_fim',
        'sabado_inicio',
        'sabado_fim',
        'domingo_inicio',
        'domingo_fim',
        'centro_cirurgico_id'
    ];

}
