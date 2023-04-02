<?php

namespace App;
use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fretes extends Model
{
  	use SoftDeletes;
	use ModelPossuiLogs;

	protected $table = 'fretes';

	protected $fillable = [
		'id',
		'comercial_id',
		'tipo_frete',
        'tipo_filtro',
        'ativado',
        'tipo_prazo',
        'prazo_minimo',
        'prazo_maximo',
	];

    public function fretesEntrega(){
        return $this->hasMany(FretesEntrega::class, 'fretes_id');
    }

    public function fretesRetirada(){
        return $this->hasMany(FretesRetirada::class, 'fretes_id');
    }


    public function comercial()
    {
        return $this->belongsTo(Comercial::class, 'comercial_id');
    }

}
