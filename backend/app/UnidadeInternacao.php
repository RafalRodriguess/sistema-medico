<?php

namespace App;

use App\UnidadeLeito;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnidadeInternacao extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'unidades_internacoes';

    protected $fillable = [
        'id',
        'nome',
        'cc_id',
        'tipo_unidade',
        'localizacao',
        'hospital_dia',
        'ativo',
        'instituicao_id'
    ];

    // Tipos de Unidades ---
    const tipos_unidade = [
        1 => 'Urgência',
        2 => 'Internação',
        3 => 'Convênio/Particular'
    ];


    public static function getTiposUnidades()
    {
        return array_keys(self::tipos_unidade);
    }

    public static function getTipoUnidadeTexto($tipo_unidade)
    {
        return self::tipos_unidade[$tipo_unidade];
    }

    public function leitos()
    {
        return $this->hasMany(UnidadeLeito::class, 'unidade_id');
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

        return $query
            ->where('nome', 'like', "%{$search}%")
            ->orWhere('localizacao', 'like', "%{$search}%");
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'id', 'instituicao_id');
    }
}
