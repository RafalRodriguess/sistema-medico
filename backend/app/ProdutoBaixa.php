<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProdutoBaixa extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'estoque_baixa_produtos';

    protected $fillable = [
        'id',
        'baixa_id',
        'quantidade',
        'id_entrada_produto'
    ];

    protected $casts = [
        'id' => 'interger',
        'baixa_id' => 'interger',
        'produto_id' => 'interger',
    ];

    public static function boot()
    {
        $recalcular_estoque = function (ProdutoBaixa $model) {
            $quantidade_antiga = $model->getOriginal('quantidade') ?? 0;
            $entrada = EstoqueEntradaProdutos::find($model->id_entrada_produto);
            if ($model->getOriginal('id_entrada_produto') == $model->id_entrada_produto) { // Caso seja um update que não altere a entrada
                // Adiciona a quantidade antiga e subtrai a nova
                DB::table('estoque_entradas_produtos')->where('id', $entrada->id)->update([
                    'quantidade_estoque' => $entrada->quantidade_estoque + $quantidade_antiga - $model->quantidade
                ]);
            } else { // Caso haja um update nos ids entrada ou seja cadastrado um novo
                $entrada_antiga = EstoqueEntradaProdutos::find($model->getOriginal('id_entrada_produto'));
                // Corrigindo entrada antiga caso seja um update para uma nova entrada
                if ($entrada_antiga) {
                    DB::table('estoque_entradas_produtos')->where('id', $entrada_antiga->id)->update([
                        'quantidade_estoque' => $entrada_antiga->quantidade_estoque + $quantidade_antiga
                    ]);
                }

                DB::table('estoque_entradas_produtos')->where('id', $entrada->id)->update([
                    'quantidade_estoque' => $entrada->quantidade_estoque - $model->quantidade
                ]);
            }
        };

        self::creating($recalcular_estoque);
        self::updating($recalcular_estoque);
        self::deleting(function ($model) {
            $entrada = EstoqueEntradaProdutos::find($model->id_entrada_produto);
            // Adiciona a quantidade da saída
            DB::table('estoque_entradas_produtos')->where('id', $entrada->id)->update([
                'quantidade_estoque' => $entrada->quantidade_estoque + $model->quantidade
            ]);
        });
        parent::boot();
    }

    public function entradaProduto()
    {
        return $this->belongsTo(EstoqueEntradaProdutos::class, 'id_entrada_produto');
    }

    public function produtos()
    {
        return $this->hasOneThrough(Produto::class, EstoqueEntradaProdutos::class, 'id', 'id', 'id_entrada_produto', 'id_produto');
    }


    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if (empty($search)) {
            return $query;
        }

        if (preg_match('/^\d+$/', $search)) {
            return $query->where('id', 'like', "{$search}%");
        }

        return $query->whereHas('produto', function ($query) use ($search) {
            $query->where('descricao', 'like', "%{$search}%");
        });
    }
}
