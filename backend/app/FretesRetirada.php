<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FretesRetirada extends Model
{
	use SoftDeletes;
	use ModelPossuiLogs;

	protected $table = 'fretes_retirada';

	protected $fillable = [
		'id',
		'fretes_id',
		'nome',
		'rua',
		'numero',
		'bairro',
		'cidade',
		'estado',
		'cep',
		'tipo_prazo_minimo',
		'tipo_prazo_maximo',
		'prazo_minimo',
		'prazo_maximo',
	];


	public function frete()
	{
		return $this->belongsTo(Fretes::class, 'fretes_id');
	}

	public function horarios()
    {
        return $this->hasMany(FretesRetiradaHorario::class, 'retirada_id');
    }

	public function scopeSearchFretesFiltros(Builder $query, string $search = '', Int $frete): Builder
	{

		// $query->where(['fretes_id', $frete]);

		$query->whereHas('frete', function ($q) use ($frete) {
			$q->where('fretes_id', $frete);
		});


		if (empty($search)) {
			return $query;
		}

		if (preg_match('/^\d+$/', $search)) {
			return $query->where('id', 'like', "{$search}%");
		}

		return $query->where('bairro', 'like', "%{$search}%")
			->orWhere('bairro', 'like', "%{$search}%")
			->orWhere('nome', 'like', "%{$search}%")
			->orWhere('rua', 'like', "%{$search}%")
			->orWhere('numero', 'like', "%{$search}%")
			->orWhere('bairro', 'like', "%{$search}%")
			->orWhere('cidade', 'like', "%{$search}%")
			->orWhere('estado', 'like', "%{$search}%")
			->orWhere('cep', 'like', "%{$search}%");


		}
}
