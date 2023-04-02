<?php

namespace App;

use App\Support\ModelPossuiLogs;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Acomodacao extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'acomodacoes';

    protected $fillable = [
        'id',
        'descricao',
        'tipo_id',
        'extra_virtual',
        'instituicao_id'
    ];

    // Tipos de Acomodação ---------
    const normal = 1;
    const clinico = 2;
    const cirurgico = 3;
    const uti = 4;
    const externo = 5;
    const bercario = 6;
    const hospital_dia = 7;
    const isolamento = 8;
    // -----------------------------

    public static function getTipos()
    {
        return [
            self::normal,
            self::clinico,
            self::cirurgico,
            self::uti,
            self::externo,
            self::bercario,
            self::hospital_dia,
            self::isolamento
        ];
    }

    public static function getTipoTexto($tipo)
    {
        $dados = [
            self::normal => 'Normal',
            self::clinico => 'Clínico',
            self::cirurgico => 'Cirurgico',
            self::uti => 'U.T.I',
            self::externo => 'Externo',
            self::bercario => 'Berçário',
            self::hospital_dia => 'Hospital Dia',
            self::isolamento => 'Isolamento'
        ];
        return $dados[$tipo];
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

        return $query->where('descricao', 'like', "%{$search}%");
    }
}
