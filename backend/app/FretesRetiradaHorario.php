<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class FretesRetiradaHorario extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'fretes_retirada_horarios';

    protected $fillable = [
		'id',
		'retirada_id',
		'dia',
		'inicio',
		'fim'
	];


}
