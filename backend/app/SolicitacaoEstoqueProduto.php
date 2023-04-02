<?php

namespace App;

use App\Casts\Checkbox;
use Illuminate\Database\Eloquent\Model;

class SolicitacaoEstoqueProduto extends Model
{
    protected $table = 'solicitacoes_estoque_prod';
    protected $fillable = [
        'solicitacoes_estoque_id',
        'produtos_id',
        'quantidade',
        'motivos_divergencia_id',
        'confirma_item',
    ];
    public $timestamps = false;

    protected $casts = [
        'confirma_item' => Checkbox::class,
    ];

    public function unidade()
    {
        return $this->hasOneThrough(Unidade::class, Produto::class, 'id', 'id', 'produtos_id', 'unidade_id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produtos_id');
    }

    public function solicitacaoEstoque()
    {
        return $this->belongsTo(SolicitacaoEstoque::class, 'solicitacoes_estoque_id');
    }

    public function motivoDivergencia()
    {
        return $this->belongsTo(MotivoDivergencia::class, 'motivos_divergencia_id');
    }
}
