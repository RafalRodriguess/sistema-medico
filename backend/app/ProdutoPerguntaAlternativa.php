<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdutoPerguntaAlternativa extends Model
{
    //
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'produto_pergunta_alternativas';

    protected $fillable = [
        'id',
        'alternativa',
        'preco',
        'quantidade_maxima_itens',
        'produto_pergunta_id',
    ];

    protected $casts = [
        'obrigatorio' => 'boolean',
        'quantidade_maxima' => 'int',
        'quantidade_minima' => 'int',
        'quantidade_maxima_itens' => 'int',
    ];
    
    public function produto_perguntas()
    {
        return $this->hasMany(ProdutoPergunta::class, 'produto_pergunta_id');
    }
}
