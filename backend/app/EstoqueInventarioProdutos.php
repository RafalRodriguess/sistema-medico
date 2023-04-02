<?php

namespace App;


use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class EstoqueInventarioProdutos extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'estoque_inventario_produtos';

    protected $fillable = [
        'id',
        'estoque_inventario_id',
        'produto_id',
        'quantidade',
        'quantidade_inventario',
        'lote'
    ];

    // public function estoque_entrada()
    // {
    //     return $this->belongsTo(EstoqueEntradas::class, 'id_entrada','id');
    // }

    public function estoqueEntradaProdutos()
    {
        return $this->belongsTo(EstoqueEntradaProdutos::class, 'lote');
    }

    public function produtos()
    {
        return $this->belongsTo(Produto::class, 'produto_id','id');
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

        return $query->whereHas('produtos', function ($query) use ($search) {
            $query->where('descricao', 'like', "%{$search}%");
          });

    }
}
