<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CodTerritorialIbge extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'codigo_territorial_ibge';

    protected $fillable = [
        'id',
        'uf',
        'municipio',
        'cod_uf',
        'cod_municipio',
    ];

    
    public function scopeGetCodMunicipio(Builder $query, string $uf, string $municipio): Builder
    {
        return $query->where('uf', $uf)->where('municipio', $municipio);
    }
}
