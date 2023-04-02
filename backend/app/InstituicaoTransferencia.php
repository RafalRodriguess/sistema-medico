<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstituicaoTransferencia extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'instituicoes_transferencia';

    protected $fillable = [
        'id',
        'descricao',
        'cnes',
        'estado',
        'cidade',
        'bairro',
        'rua',
        'numero',
        'complemento',
        'cep',
        'instituicao_id',
        'telefone',
        'email'
    ];

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicao_id');
    }

    public function scopeSearchByDescricao(Builder $query, string $descricao = ''): Builder
    {
        if(empty($descricao)) return $query;

        return $query->where('descricao', 'like', "%{$descricao}%");
    }

}
