<?php

namespace App;

use App\Support\ModelOverwrite;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class InstituicaoSolicitacaoCompra extends Model
{
    use ModelOverwrite;
    use SoftDeletes;
    use TraitLogInstituicao;
   
    protected $table = 'solicitacao_compras';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'data_solicitacao',
        'data_maxima',
        'data_impressao',
        'setores_exames_id',
        'cod_usuario',
        'nome_solicitante',
        'motivo_pedido_id',
        'instituicao_id',
        'comprador_id',
        'estoque_id',
        'sol_agrup',
        'servico_produto',
        'urgente',
        'solicitacao_opme',
        'atendimento',
        'pre_int',
        'av_cirurgia',
        'data_maxima_apoio_cotacao',
    ];

    protected $dates = [
        'data_solicitacao',
        'data_maxima',
        'data_impressao',
        'data_maxima_apoio_cotacao',
    ];

    protected $allowed_overwrite = [
        SolicitacaoComprasProduto::class
    ];

    public function solicitacaoComprasProdutos()
    { 
        return $this->hasMany(SolicitacaoComprasProduto::class, 'solicitacao_compras_id');
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

        return $query->where('nome_solicitante', 'like', "%{$search}%");
    }

    public function solicitacoeCompra()
    {
        return $this->hasMany(SolicitacaoEstoque::class, 'instituicao_id');
    }

}
