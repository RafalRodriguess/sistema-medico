<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ProdutoBaixa as EstoqueProdutoBaixa;
use App\Support\TraitLogInstituicao;

class SaidaEstoqueProduto extends Model
{
    use TraitLogInstituicao;
    
    protected $table = 'saida_estoque_produtos';
    protected $fillable = [
        'saida_estoque_id',
        'codigo_de_barras',
        'estoque_baixa_produtos_id',
        'valor'
    ];

    public function saidaEstoque()
    {
        return $this->belongsTo(SaidaEstoque::class, 'saida_estoque_id');
    }

    public function baixaProduto()
    {
        return $this->belongsTo(EstoqueProdutoBaixa::class, 'estoque_baixa_produtos_id');
    }

    public function baixa()
    {
        return $this->hasOneThrough(EstoqueBaixa::class, EstoqueProdutoBaixa::class, 'id', 'id', 'estoque_baixa_produtos_id', 'baixa_id');
    }

    public function entradaProduto()
    {
        return $this->hasOneThrough(EstoqueEntradaProdutos::class, EstoqueProdutoBaixa::class, 'id', 'id', 'estoque_baixa_produtos_id', 'id_entrada_produto');
    }
}
