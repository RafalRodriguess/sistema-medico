<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PedidoTimeline extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'pedido_timelines';

    protected $fillable = [
        'id',
        'pedidos_id',
        'usuario_id',
        'usuario_type',
        'descricao',
        'data_mudanca',
        'mudancas'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedidos_id');
    }
}
