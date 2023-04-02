<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnderecoEntrega extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'endereco_entregas';

    protected $fillable = [
        'id',
        'nome',
        'cpf',
        'rua',
        'numero',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'complemento',
        'referencia',
        'frete_id_retirada',
    ];

    public function pedido()
    {
        return $this->hasMany(Pedido::class,'id');
    }



    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search))
        {
            return $query;
        }

        if(preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%");
        }

        return $query->where('nome', 'like', "%{$search}%");
    }

}
