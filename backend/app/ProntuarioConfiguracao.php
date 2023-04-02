<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProntuarioConfiguracao extends Model
{
    protected $table = 'prontuario_configuracoes';

    protected $fillable = [
        'id',
        'instituicao_id',
        'descricao',
        'status',
    ];

    public function itens()
    {
        return $this->hasMany(ProntuarioConfiguracaoItem::class, 'prontuario_configuracao_id')->where('status', 1);
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search)) return $query;

        if(preg_match('/^\d+$/', $search)) return $query->where('id','like', "{$search}%");

        return $query->where('descricao', 'like', "%{$search}%");
    }
}
