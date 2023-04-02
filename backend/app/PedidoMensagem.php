<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class PedidoMensagem extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'mensagens_pedidos';

    protected $fillable = [
        'id',
        'pedido_id',
        'remetente',
        'mensagem',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }


}
