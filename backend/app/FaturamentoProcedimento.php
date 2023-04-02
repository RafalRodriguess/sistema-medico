<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FaturamentoProcedimento extends Model
{
    use TraitLogInstituicao;
    use SoftDeletes;

    protected $table = "faturamento_has_procedimentos";

    protected $fillable = [
        'faturamento_id',
        'data_vigencia',
        'procedimento_id',
        'descricao',
        'vl_honorario',
        'vl_operacao',
        'vl_total',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'data_vigencia' => 'date'
    ];
}
