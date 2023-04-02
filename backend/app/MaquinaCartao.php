<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaquinaCartao extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = "maquinas_cartoes";

    protected $fillable = [
        'instituicao_id',
        'descricao',
        'codigo',
        'taxa_debito',
        'taxa_credito',
        'dias_parcela_debito',
        'dias_parcela_credito',
    ];

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicao_id');
    }

    public function taxa()
    {
        return $this->hasMany(TaxaCartao::class, 'maquina_id');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search)) return $query;

        if(preg_match('/^\d+$/', $search)) return $query->where('id','like', "{$search}%");

        return $query->where('descricao', 'like', "%{$search}%");
    }
}
