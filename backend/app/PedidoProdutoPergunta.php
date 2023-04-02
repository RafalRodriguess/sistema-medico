<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PedidoProdutoPergunta extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'pedido_produtos_perguntas';

    protected $fillable = [
        'id',
        'texto_pergunta',
        'texto_resposta',
        'valor',
        'tipo_pergunta',
        'quantidade',
        'pedido_produtos_id',
        'pergunta_id',
        'alternativa_id'
    ];

    public function pergunta()
    {
        return $this->belongsTo(ProdutoPergunta::class, 'pergunta_id');
    }

    public function alternativa()
    {
        return $this->belongsTo(ProdutoPerguntaAlternativa::class, 'alternativa_id');
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

        return $query->where('nome', 'like', "%{$search}%");
    }

}
