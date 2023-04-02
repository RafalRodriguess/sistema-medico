<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PedidoProduto extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'pedido_produtos';

    protected $fillable = [
        'id',
        'valor',
        'imagem',
        'nome',
        'nome_farmaceutico',
        'breve_descricao',
        'quantidade',
        'produto_id',
        'pedido_id'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function perguntas()
    {
        return $this->hasMany(PedidoProdutoPergunta::class,'pedido_produtos_id');
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
