<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FretesEntrega extends Model
{
	use SoftDeletes;
	use ModelPossuiLogs;

	protected $table = 'fretes_entrega';

	protected $fillable = [
		'fretes_id',
		'tipo_frete',
		'valor',
		'valor_minimo',
		'cidade',
		'bairro',
		'cep_inicio',
		'cep_fim',
		'tipo_prazo',
		'prazo_minimo',
		'prazo_maximo',
	];

	public function frete()
    {
        return $this->belongsTo(Fretes::class, 'fretes_id');
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
			->orWhere('cidade', 'like', "%{$search}%")
			->orWhere('bairro', 'like', "%{$search}%")
			->orWhere('cep_inicio', 'like', "%{$search}%")
			->orWhere('cep_fim', 'like', "%{$search}%");
	}
}
