<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegraCobranca extends Model
{
    use TraitLogInstituicao;
    use SoftDeletes;

    protected $table = 'regras_cobranca';

    protected $fillable = [
        'instituicao_id',
        'descricao',
        'cir_mesma_via',
        'cir_via_diferente',
        'horario_especial',
        'base_via_acesso',
        'internacao',
        'ambulatorial',
        'urgencia_emergencia',
        'externo',
        'home_care',
    ];

    protected $casts = [
        'horario_especial' => 'boolean',
        'internacao' => 'boolean',
        'ambulatorial' => 'boolean',
        'urgencia_emergencia' => 'boolean',
        'externo' => 'boolean',
        'home_care' => 'boolean',
    ];

    public function itens()
    {
        return $this->hasMany(RegraCobrancaItem::class, 'regra_cobranca_id');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search)) return $query;

        if(preg_match('/^\d+$/', $search)) return $query->where('id','like', "{$search}%");

        return $query->where('descricao', 'like', "%{$search}%");
    }
}
