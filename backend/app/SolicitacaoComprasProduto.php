<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitacaoComprasProduto extends Model
{
    protected $table = 'solicitacao_compras_produtos';
    protected $fillable = [
        'solicitacao_compras_id',
        'produto_id',
        'pessoa_id',
        'qtd_solicitada',
        'oferta_max',
    ];
    
    public function solicitacaoEstoque()
    {
        return $this->belongsTo(InstituicaoSolicitacaoCompras::class, 'solicitacao_compras_id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produtos_id');
    }

    public function pessoa()
    {
        return $this->belongsTo(Produto::class, 'pessoa_id');
    }
    
} 
