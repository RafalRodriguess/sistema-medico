<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContaBancaria extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'contas_bancarias';

    protected $fillable = [
        'id',
        'bank_name',
        'bank_code',
        'agencia',
        'agencia_dv',
        'conta',
        'conta_dv',
        'type',
        'documento_titular',
        'nome_titular',
        'id_pagarme'

    ];


}
