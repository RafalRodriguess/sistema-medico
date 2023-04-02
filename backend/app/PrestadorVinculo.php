<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PrestadorVinculo extends Model
{
    use TraitLogInstituicao;
    
    protected $table = 'prestadores_vinculos';

    protected $fillable = [
        'prestador_id',
        'vinculo_id'
    ];


    const cooperado = 1;
    const funcionario = 2;
    const estagiario = 3;
    const voluntario = 4;

    public static function getVinculos()
    {
        return [
            self::cooperado,
            self::funcionario,
            self::estagiario,
            self::voluntario,
        ];
    }

    public static function getVinculoTexto($vinculo){
        $dados = [
            1 => 'Cooperado',
            2 => 'Funcionário',
            3 => 'Estagiário',
            4 => 'Voluntário',
        ];
        return $dados[$vinculo];
    }

}
