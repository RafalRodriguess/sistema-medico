<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'pedidos';

    protected $fillable = [
        'id',
        'valor_total',
        'valor_entrega',
        'forma_entrega',
        'data_entrega',
        'prazo_tipo',
        'prazo_tipo_minimo',
        'prazo_tipo_maximo',
        'prazo_maximo',
        'prazo_minimo',
        'status_pedido',
        'status_pagamento',
        'codigo_transacao',
        'cartoes_id',
        'parcelas',
        'valor_parcela',
        'free_parcela',
        'observacao',
        'comercial_id',
        'usuarios_id',
        'endereco_entregas_id',
        'entrega_cliente',
        'entrega_sistema',
        'entrega_comercial',
        'forma_pagamento',
        'troco_dinheiro',
    ];

    protected $casts = [
        'data_entrega' => 'datetime',
    ];

    static function coresStatus($status)
    {
        $cores = [
            'pendente' => '#6c6c6d',
            'aprovado' => '#0095fff0',
            'disponivel' => '#b6ca05',
            'enviado' => '#b6ca05',
            'cancelado' => '#ff5722',
            'entregue' => '#00cc81',
        ];
        return $cores[$status];
    }

    static function iconesStatus($status)
    {
        $cores = [
            'pendente' => 'fa fa-clock',
            'aprovado' => 'fa fa-check',
            'disponivel' => 'fa fa-shopping-bag',
            'enviado' => 'mdi mdi-motorbike',
            'cancelado' => 'fa fa-times',
            'entregue' => 'fas fa-handshake',
        ];
        return $cores[$status];
    }

    protected $appends = ['valor_somatorio'];

    public function getValorSomatorioAttribute()
    {
        $valor_somatorio = 0;
        foreach ($this->produtos as $produto) {
            $valor_somatorio += $produto->valor * $produto->quantidade;

            foreach ($produto->perguntas as $pergunta) {
                $valor_somatorio += $pergunta->valor * $pergunta->quantidade;
            }
        }

        return $valor_somatorio;
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuarios_id');
    }

    public function comercial()
    {
        return $this->belongsTo(Comercial::class, 'comercial_id');
    }

    public function entrega()
    {
        return $this->belongsTo(EnderecoEntrega::class, 'endereco_entregas_id');
    }

    public function produtos()
    {
        return $this->hasMany(PedidoProduto::class, 'pedido_id');
    }

    public function mensagem()
    {
        return $this->hasMany(PedidoMensagem::class, 'pedido_id');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if (empty($search)) {
            return $query;
        }

        if (preg_match('/^\d+$/', $search)) {
            return $query->where('id', 'like', "{$search}%");
        }

        return $query->where('nome', 'like', "%{$search}%");
    }

    public function scopeSearchLista(Builder $query, string $search = '', string $status = '', Comercial $comercial, int $unreadMessages): Builder
    {



        $query->where('comercial_id', $comercial->id)->join('endereco_entregas', 'endereco_entregas.id', '=', 'pedidos.endereco_entregas_id')
            ->select('endereco_entregas.*', 'pedidos.*')->orderBy('pedidos.id', 'desc');

        //busca as mensagens nao vistas
        $query->with(["mensagem" => function($q){
            $q->where('mensagens_pedidos.visto', '=', 0);
            $q->where('mensagens_pedidos.remetente', '=', 'cliente');
        }]);



        if ($status) {
            $query->where('status_pedido', 'like', $status);
        }


        if ($unreadMessages) {
            $query->whereHas('mensagem',function($q) use ($unreadMessages){
                $q->where('remetente', 'cliente');
                $q->where('visto', 0);
            });
        }

        if ($search) {
            return $query->where('nome', 'like', "%{$search}%");
        }

        if (preg_match('/^\d+$/', $search)) {
            return $query->where('id', 'like', "{$search}%");
        }

        return $query;
    }
}
