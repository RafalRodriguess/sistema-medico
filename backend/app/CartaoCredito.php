<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartaoCredito extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'cartoes_credito';

    protected $fillable = [
        'id',
        'descricao',
        'bandeira',
        'limite',
        'fechamento',
        'vencimento',
        'instituicao_id'
    ];

    const opcoes_bandeira = [
        1 => 'Mastercard',
        2 => 'Visa',
        3 => 'American Express',
        4 => 'Hipercard',
        5 => 'Elo',
        6 => 'Outros',
    ];

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

        return $query->where('descricao', 'like', "%{$search}%");
    }
}
