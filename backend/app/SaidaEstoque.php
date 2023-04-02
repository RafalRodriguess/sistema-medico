<?php

namespace App;

use App\Casts\Checkbox;
use App\Support\ModelOverwrite;
use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;

class SaidaEstoque extends Model
{
    use ModelOverwrite;
    use ModelPossuiLogs;

    protected $table = 'saida_estoque';
    protected $fillable = [
        'observacoes',
        'estoque_baixa_id',
        'estoques_id',
        'usuarios_id',
        'instituicoes_id',
        'tipo_destino',
        'agendamento_id',
        'pessoa_id',
        'centros_custos_id',
        'gerar_conta'
    ];
    public $timestamps = false;

    protected $allowed_overwrite = [
        SaidaEstoqueProduto::class,
        ContaReceber::class
    ];

    protected $casts = [
        'gerar_conta' => Checkbox::class
    ];

    public const destino_saida = [
        1 => 'Paciente',
        2 => 'Agendamento',
    ];

    public function estoque()
    {
        return $this->belongsTo(Estoque::class, 'estoques_id');
    }

    public function paciente()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }

    public function agendamento()
    {
        return $this->belongsTo(Agendamentos::class, 'agendamento_id');
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicoes_id');
    }

    public function usuario()
    {
        return $this->belongsTo(InstituicaoUsuario::class, 'usuarios_id');
    }

    public function baixaEstoque()
    {
        return $this->belongsTo(EstoqueBaixa::class, 'estoque_baixa_id');
    }

    public function produtosSaida()
    {
        return $this->hasMany(SaidaEstoqueProduto::class, 'saida_estoque_id');
    }

    public function produtosBaixa()
    {
        return $this->hasManyThrough(ProdutoBaixa::class, SaidaEstoqueProduto::class, 'saida_estoque_id', 'id', 'id', 'estoque_baixa_produtos_id');
    }

    public function contasReceber()
    {
        return $this->hasMany(ContaReceber::class, 'saidas_estoque_id');
    }

    public function centroDeCusto()
    {
        return $this->belongsTo(CentroCusto::class, 'centros_custos_id');
    }

    public function getProdutosSaidaCompleto(): array
    {
        $instituicao = Instituicao::find(request()->session()->get('instituicao'));
        $baixa_estoque = $this->baixaEstoque()->first();

        $query_estoque = EstoqueEntradaProdutos::buscarProdutosEmEstoque('', $baixa_estoque);

        $produtos_saida = DB::table('saida_estoque_produtos')
            ->select(
                'estoque.*',
                'estoque_baixa_produtos.quantidade',
                'codigo_de_barras',
                'estoque_baixa_produtos_id'
            )
            ->join('saida_estoque', 'saida_estoque.id', 'saida_estoque_id')
            ->join('estoque_baixa_produtos', 'estoque_baixa_produtos.id', 'estoque_baixa_produtos_id')
            ->join('produtos', 'produtos.id', 'estoque_baixa_produtos.produto_id')
            ->join(DB::raw("({$query_estoque->toSql()}) as estoque"), 'estoque.lote', 'estoque_baixa_produtos.lote')
            ->where('saida_estoque_id', $this->id)
            ->where('instituicoes_id', $instituicao->id);
        $produtos_saida->setBindings(array_merge(
            $query_estoque->getBindings(),
            $produtos_saida->getBindings()
        ));


        return $produtos_saida->get()->toArray();
    }

    public function delete()
    {
        // Garantindo a deleção das baixas de estoque pois essas não tem onDelete na relação
        return DB::transaction(function () {
            $usuario_logado = request()->user('instituicao');
            $baixa_estoque = $this->baixaEstoque()->first();
            if ($baixa_estoque) {
                $baixa_estoque->criarLogExclusao($usuario_logado);
                $baixa_estoque->delete();
            }
            return DB::table($this->table)->where('id', $this->attributes['id'])->delete();
        });
    }

    /**
     * Busca o motivo padrão para a saida de estoque
     * @return MotivoBaixa|null
     */
    public static function buscarMotivoSaidaEstoque(): ?MotivoBaixa
    {
        $instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        return MotivoBaixa::firstOrCreate([
            'instituicao_id' => $instituicao->id,
            'slug' => 'saida-de-estoque'
        ], [
            'descricao' => 'Saida de estoque'
        ]);
    }
}
