<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\CentroCusto;

class Origem extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'origens';

    protected $fillable = [
        'id',
        'descricao',
        'tipo_id',
        'cc_id',
        'ativo',
        'instituicao_id',
    ];

    // Tipo ---------
    const urgencia = 1;
    const ambulatorial = 2;
    const externo = 3;
    const internacao = 4;
    const homecare = 5;
    const administrativo = 6;
    // --------------

    public static function getTipos()
    {
        return [
            self::urgencia,
            self::ambulatorial,
            self::externo,
            self::internacao,
            self::homecare,
            self::administrativo,
        ];
    }

    public static function getTipoTexto($tipo)
    {
        $dados = [
            self::urgencia => 'Urgência',
            self::ambulatorial => 'Ambulatorial',
            self::externo => 'Externo',
            self::internacao => 'Internação',
            self::homecare => 'Homecare',
            self::administrativo => 'Administrativo'
        ];
        return $dados[$tipo] ?? 'Não especificado';
    }


    public function belongsToCentroCusto()
    {
        return $this->belongsTo(CentroCusto::class, $this->cc_id);
    }

    public function scopeCentroCusto(Builder $query) {
        return $query->with(['belongsToCentroCusto' => function($query){
            $query->where('id', $this->cc_id);
        }])->first();
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
