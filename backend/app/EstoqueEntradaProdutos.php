<?php

namespace App;


use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;

class EstoqueEntradaProdutos extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'estoque_entradas_produtos';

    protected $fillable = [
        'id',
        'id_produto',
        'quantidade',
        'lote',
        'id_entrada',
        'valor',
        'valor_custo',
        'validade'
    ];

    public static function boot()
    {
        self::creating(function (self $model) {
            $model->quantidade_estoque = $model->quantidade;
        });
        self::updating(function (self $model) {
            $quantidade_antiga = (float)($model->getOriginal('quantidade') ?? 0);
            $diff = $model->quantidade - $quantidade_antiga;
            $model->quantidade_estoque = max($model->quantidade_estoque + $diff, 0);
        });

        parent::boot();
    }

    protected $casts = [
        'id_produto' => 'interger',
    ];

    public function fornecedor()
    {
        return $this->hasOneThrough(Pessoa::class, EstoqueEntradas::class, 'id', 'id', 'id_entrada', 'id_fornecedor');
    }

    public function entrada()
    {
        return $this->belongsTo(EstoqueEntradas::class, 'id_entrada', 'id');
    }
    
    public function entradaTrashed()
    {
        return $this->belongsTo(EstoqueEntradas::class, 'id_entrada', 'id')->withTrashed();
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto', 'id');
    }

    public function produtosSolicitacaoAtendidos()
    {
        return $this->hasMany(SolicitacaoEstoqueProdutoAtendido::class, 'id_entrada_produto');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if (empty($search)) {
            return $query;
        }

        if (preg_match('/^\d+$/', $search)) {
            return $query->where('id', 'like', "{$search}%");
        }

        return $query->whereHas('produtos', function ($query) use ($search) {
            $query->where('descricao', 'like', "%{$search}%");
        });
    }

    /**
     *  Retorna um QueryBuilder com a query que busca os lotes de produtos existentes em estoque, retornando
     *  o lote e a quantidade de produtos em cada lote
     *  OBS.: Esta query já verifica as instituições dos produtos selecionados
     *  @param \App\EstoqueBaixa|null $estoque_baixa_id O ID da baixa cujos itens removidos de estoque devem ser adicionados novamente ao saldo
     *  @return \Illuminate\Database\Eloquent\Builder|array Um query builder com a query de busca de lotes em estoque pronta, caso $no_union seja
     * true, retorna um array com as 2 queries
     */
    public static function lotesProdutosEmEstoque(EstoqueBaixa $baixa = null, Instituicao $instituicao = null, bool $no_union = false)
    {
        $instituicao = !empty($instituicao) ? $instituicao : Instituicao::findOrFail(request()->session()->get('instituicao'));
        // Preparando as entradas 
        $lotes = self::selectRaw('
            estoque_entradas_produtos.*,
            estoque_entradas_produtos.quantidade_estoque as saldo_total
        ')->with([
            'produto',
            'produto.unidade',
            'fornecedor',
            'entrada'
        ])
            ->whereHas('produto', function (Builder $query) {
                $query->whereNull('produtos.deleted_at');
            })
            ->whereHas('entrada', function (Builder $query) use ($instituicao) {
                $query->where('estoque_entradas.instituicao_id', $instituicao->id)
                    ->whereNull('estoque_entradas.deleted_at');
            })
            ->where('quantidade_estoque', '>', 0)
            ->whereNull('estoque_entradas_produtos.deleted_at');

        // Para adicionar os produtos removidos na baixa selecionada
        if (!empty($baixa)) {
            $produtos_baixa = $baixa->estoqueBaixaProdutos()->get()->pluck('id_entrada_produto')->toArray();
            $lotes->whereNotIn('estoque_entradas_produtos.id', $produtos_baixa);
            $lotes_afetados = self::selectRaw('
                    estoque_entradas_produtos.*,
                    (estoque_entradas_produtos.quantidade_estoque + IFNULL(estoque_baixa_produtos.quantidade, 0)) as saldo_total
                ')
                ->with([
                    'produto',
                    'produto.unidade',
                    'fornecedor',
                    'entrada'
                ])
                ->whereHas('produto', function (Builder $query) {
                    $query->whereNull('produtos.deleted_at');
                })
                ->whereHas('entrada', function (Builder $query) use ($instituicao) {
                    $query->where('estoque_entradas.instituicao_id', $instituicao->id)
                        ->whereNull('estoque_entradas.deleted_at');
                })
                ->join('estoque_baixa_produtos', 'estoque_baixa_produtos.id_entrada_produto', 'estoque_entradas_produtos.id')
                ->where('estoque_baixa_produtos.baixa_id', $baixa->id)
                ->whereNull([
                    'estoque_entradas_produtos.deleted_at',
                    'estoque_baixa_produtos.deleted_at'
                ]);

            if ($no_union) {
                return [
                    $lotes,
                    $lotes_afetados
                ];
            }
            $lotes->union($lotes_afetados);
        }

        return $lotes;
    }

    /**
     * Retorna um QueryBuilder com uma busca dos produtos em estoque e todos os seus dados necessários,
     * sejam dados dos produtos, fornecedores ou de seus lotes (dados que estão todos em tabelas diferentes)
     * @param Instituicao $instituicao A instituição cuja busca será feita
     * @param string $search A busca de descrição ou lote de produto
     * @param EstoqueBaixa|null $baixa_estoque A baixa de estoque cujos produtos serão isentos da contagem
     * @param array|int $entradas Os ids das entradas a serem buscadas ou um único id para ser buscado
     * @return \Illuminate\Database\Eloquent\Builder Um query builder com a query de busca de produtos em estoque pronta
     */
    public static function buscarProdutosEmEstoque(string $search = '', EstoqueBaixa $baixa_estoque = null, $entradas = [])
    {
        $instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        $queries = self::lotesProdutosEmEstoque($baixa_estoque, $instituicao, true);

        if(empty($baixa_estoque)) {
            $queries = [$queries];
        }
        foreach ($queries as $query) {
            $query->with([
                'produto.unidade',
                'produto.classe'
            ]);

            // Buscando caso necessário
            if (!empty($search)) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('produto', function ($query) use ($search) {
                        $query->where('produtos.descricao', 'like', "%{$search}%");
                    })->orWhere('estoque_entradas_produtos.lote', 'like', "%{$search}%");
                });
            }

            if (!empty($entradas)) {
                if (is_array($entradas)) {
                    $query->whereIn('estoque_entradas_produtos.id', $entradas);
                } else {
                    $query->where('estoque_entradas_produtos.id', $entradas);
                }
            }
        }
        
        if(count($queries) > 1) {
            return $queries[0]->union($queries[1]);
        } else {
            return $queries[0];
        }
    }
}
