<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Model;

class SolicitacaoEstoqueProdutoAtendido extends Model
{
    use ModelPossuiLogs;

    protected $table = 'solicitacao_estoque_prod_atendidos';
    protected $fillable = [
        'solicitacoes_estoque_id',
        'quantidade',
        'codigo_de_barras',
        'id_entrada_produto',
    ];

    public function solicitacaoEstoque()
    {
        return $this->belongsTo(SolicitacaoEstoque::class, 'solicitacoes_estoque_id');
    }

    public function produto()
    {
        return $this->hasOneThrough(Produto::class, EstoqueEntradaProdutos::class, 'id', 'id', 'id_entrada_produto', 'id_produto');
    }

    public function entradaProduto()
    {
        return $this->belongsTo(EstoqueEntradaProdutos::class, 'id_entrada_produto');
    }

    public function estoqueEntrada()
    {
        return $this->hasOneThrough(EstoqueEntradas::class, EstoqueEntradaProdutos::class, 'id', 'id', 'id_entrada_produto', 'id_entrada');
    }
}
