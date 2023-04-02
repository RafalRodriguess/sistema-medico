<?php

namespace App;

use App\Support\ModelPossuiLogs;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaCirurgica extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'salas_cirurgicas';

    protected $fillable = [
        'id',
        'descricao',
        'sigla',
        'tempo_minimo_preparo',
        'tempo_minimo_utilizacao',
        'tipo',
        'centro_cirurgico_id',
    ];

    // Tipos ---------
    const eletiva = 1;
    const cirurgica = 2;
    const urgencia = 4;
    const mista = 3;
    // ---------------


    public static function getTipos()
    {
        return [
            self::eletiva,
            // self::cirurgica,
            self::urgencia,
            self::mista
        ];
    }

    public static function getTipoTexto($tipo)
    {
        $dados = [
            self::eletiva => 'Eletiva',
            self::cirurgica => 'Cirúrgica',
            self::urgencia => 'Urgência',
            self::mista => 'Mista'
        ];
        return $dados[$tipo];
    }


    public function centroCirurgico()
    {
        return $this->belongsTo(CentroCirurgico::class, 'centro_cirurgico_id');
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'id', 'instituicao_id');
    }
    
    public function cirurgias()
    {
        return $this->belongsToMany(Cirurgia::class, 'cirurgias_salas', 'sala_id', 'cirurgia_id');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search)) return $query;

        if(preg_match('/^\d+$/', $search)) return $query->where('id','like', "{$search}%");

        return $query->where('descricao', 'like', "%{$search}%");
    }
}
