<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conta extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'contas';

    protected $fillable = [
        'id',
        'descricao',
        'tipo',
        'banco',
        'agencia',
        'conta',
        'situacao',
        'instituicao_id',
        'saldo_inicial'
    ];

    const opcoes_tipo = [
        1 => 'Caixa',
        2 => 'Conta Corrente',
        3 => 'Aplicação',
        4 => 'Conta garantida',
        5 => 'Corretora',
        6 => 'Outros'
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

    public function saldo()
    {
        
        $total_recebidas = $this->BelongsTo(ContaReceber::class, 'id', 'conta_id')->where('status', 1)->sum('valor_pago');
        $total_pagas = $this->BelongsTo(ContaPagar::class, 'id', 'conta_id')->where('status', 1)->sum('valor_pago');

        return $this->saldo_inicial + $total_recebidas - $total_pagas;
    }
}
