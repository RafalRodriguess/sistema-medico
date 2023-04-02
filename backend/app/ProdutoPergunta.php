<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdutoPergunta extends Model
{
    //
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'produto_perguntas';

    protected $fillable = [
        'id',
        'titulo',
        'obrigatorio',
        'tipo',
        'quantidade_maxima',
        'quantidade_minima',
        'produto_id',
    ];

    protected $casts = [
        'obrigatorio' => 'boolean',
    ];
    

    public function produtos()
    {
        return $this->belongsTo(Produtos::class, 'produto_id');
    }

    public function produto_pergunta_alternativas()
    {
        return $this->hasMany(ProdutoPerguntaAlternativa::class, 'produto_pergunta_id');
    }

    public function scopeSearch(Builder $query, string $search = '', string $tipo): Builder
    {
        if ($tipo != '') {
            $query->where('tipo', "{$tipo}");
        }

        if(empty($search))
        {
            return $query;
        }

        if(preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%");
        }

        return $query->where('titulo', 'like', "%{$search}%");
    }
}
