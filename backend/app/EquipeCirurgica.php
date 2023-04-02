<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipeCirurgica extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'equipes_cirurgicas';

    protected $fillable = [
        'id',
        'descricao',
    ];

    // Tipos Prestadores -----------
    const tipos_prestadores = [
        1 => 'Cirurgiao',
        2 => 'Auxiliar',
        3 => 'Enfermeiro',
        4 => 'Instrumentador',
        5 => 'Tecnico_enfermage',
        6 => 'Residente',
        7 => 'Anestesista'
    ];
    // -----------------------------

    public static function getTipos()
    {
        return array_keys(self::tipos_prestadores);
    }

    public static function getTipoTexto($tipo)
    {
        return self::tipos_prestadores[$tipo];
    }


    public function equipeCirurgicaPrestadores()
    {
        return $this->belongsToMany(Prestador::class, 'equipes_cirurgicas_prestadores',            'equipe_cirurgica_id', 'prestador_id')->withPivot('tipo');
    }

    public function scopeSearchByDescricao(Builder $query, string $descricao = ''): Builder
    {
        if ($descricao != '') $query->where('descricao', 'like', "%{$descricao}%");

        return $query;
    }
}
