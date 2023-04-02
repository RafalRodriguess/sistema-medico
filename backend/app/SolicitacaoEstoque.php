<?php

namespace App;

use App\Casts\Checkbox;
use App\ProdutoBaixa as EstoqueBaixaProduto;
use App\Support\ModelOverwrite;
use App\Support\ModelPossuiLogs;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SolicitacaoEstoque extends Model
{
    use ModelOverwrite;
    // use ModelPossuiLogs;
    use TraitLogInstituicao;
    use SoftDeletes;

    protected $table = 'solicitacoes_estoque';
    protected $fillable = [
        'destino',
        'instituicoes_id',
        'estoque_origem_id',
        'urgente',
        'observacoes',
        'setores_exame_id',
        'unidades_internacoes_id',
        'estoque_destino_id',
        'agendamento_atendimentos_id',
        'instituicoes_prestadores_id',
        'atendida',
        'data_atendimento',
        'estoque_baixa_id'
    ];

    protected $casts = [
        'urgente' => Checkbox::class,
        'data_atendimento' => 'datetime'
    ];

    protected $allowed_overwrite = [
        SolicitacaoEstoqueProduto::class,
        SolicitacaoEstoqueProdutoAtendido::class
    ];

    public const opcoes_destino = [
        1 => 'Paciente',
        2 => 'Setor',
        3 => 'Estoque'
    ];

    public static function boot()
    {
        parent::boot();

        // Depois de deletar
        static::deleted(function (SolicitacaoEstoque $item) {
            if (!empty($item->estoque_baixa_id)) {
                $baixa = EstoqueBaixa::find($item->estoque_baixa_id);
                $baixa->estoqueBaixaProdutos()->delete();
                $baixa->delete();
            }
        });
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicoes_id');
    }

    public function solicitacaoEstoqueProdutos()
    {
        return $this->hasMany(SolicitacaoEstoqueProduto::class, 'solicitacoes_estoque_id');
    }

    public function produtos()
    {
        return $this->hasManyThrough(Produto::class, SolicitacaoEstoqueProduto::class, 'solicitacoes_estoque_id', 'id', 'id', 'produtos_id');
    }

    public function estoqueOrigem()
    {
        return $this->belongsTo(Estoque::class, 'estoque_origem_id');
    }

    /**
     * O tipo de estoque de destino depende do atributo estoque,
     * assim como descrito na constante opcoes_destino
     * caso destino = estoque
     * @return Estoque
     * caso destino = paciente
     * @return array {
     *      @property AgendamentoAtendimento agendamento_atendimento
     *      @property InstituicoesPrestadores instituicao_prestador
     * }
     * caso destino = setor
     * @return array {
     *      @property UnidadeInternacao unidade_internacao
     *      @property SetorExame setor
     * }
     */
    public function estoqueDestino()
    {
        switch ($this->attributes['destino']) {
            case 1:
                return [
                    'agendamento_atendimento' => $this->belongsTo(AgendamentoAtendimento::class, 'agendamento_atendimentos_id')
                        ->with([
                            'pessoa',
                            'agendamento',
                            'agendamento.instituicoesPrestadores',
                            'agendamento.instituicoesPrestadores.prestador',
                            'agendamento.instituicoesPrestadores.instituicao'
                        ]),
                    'instituicao_prestador' => $this->belongsTo(InstituicoesPrestadores::class, 'instituicoes_prestadores_id')
                ];
            case 2:
                return [
                    'unidade_internacao' => $this->belongsTo(UnidadeInternacao::class, 'unidades_internacoes_id'),
                    'setor' => $this->belongsTo(SetorExame::class, 'setores_exame_id')
                ];
            case 3:
                return $this->belongsTo(Estoque::class, 'estoque_destino_id');
        }
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if (empty($search)) {
            return $query;
        }

        if (preg_match('/^\d+$/', $search)) {
            return $query->where('id', 'like', "{$search}%");
        }

        return $query->join('estoques', "{$this->table}.estoque_destino_id", '=', 'estoques.id')->where('atendida', '=', 0)->where('estoques.descricao', 'like', "%{$search}%");
    }

    public function produtosAtendidos()
    {
        return $this->hasMany(SolicitacaoEstoqueProdutoAtendido::class, 'solicitacoes_estoque_id');
    }

    public function atendimento()
    {
        return $this->belongsTo(AgendamentoAtendimento::class, 'agendamento_atendimentos_id');
    }

    public function produtosAtendidosCompleto()
    {
        return $this->produtosAtendidos()
            ->with([
                'entradaProduto',
                'produto',
                'produto.unidade'
            ]);
    }

    public function entradaProdutosAtendidos()
    {
        return $this->hasManyThrough(EstoqueEntradaProdutos::class, SolicitacaoEstoqueProdutoAtendido::class, 'solicitacoes_estoque_id', 'id', 'id', 'id_entrada_produto');
    }

    public function baixaEstoque()
    {
        return $this->belongsTo(EstoqueBaixa::class, 'estoque_baixa_id');
    }

    public function baixaEstoqueProdutos()
    {
        return $this->hasManyThrough(EstoqueBaixaProduto::class, EstoqueBaixa::class, 'id', 'baixa_id', 'estoque_baixa_id');
    }

    /**
     * Busca o motivo padrão para a baixa de estoque
     * @return MotivoBaixa|null
     */
    public static function buscarMotivoSolicitacaoEstoque(): ?MotivoBaixa
    {
        $instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        return MotivoBaixa::firstOrCreate([
            'instituicao_id' => $instituicao->id,
            'slug' => 'solicitacao-de-estoque'
        ], [
            'descricao' => 'Solicitação de estoque'
        ]);
    }
}
