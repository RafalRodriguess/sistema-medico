<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class InstituicoesAgenda extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    private static $cacheSetores = [];

    private static $cacheSetoresCarregados = [];

    protected $table = 'instituicoes_agenda';

    protected $fillable = [
        'id',
        'referente',
        'tipo',
        'dias_continuos',
        'dias_unicos',
        'hora_inicio',
        'hora_fim',
        'hora_intervalo',
        'duracao_intervalo',
        'duracao_atendimento',
        'grupos_instituicoes_id',
        'procedimentos_instituicoes_id',
        'instituicoes_prestadores_id',
        'setor_id',
        'faixa_etaria',
        'obs'
    ];

    const todas = 'todas';
    const menor_12 = 'menor_12';
    const acima_12 = 'acima_12';
    const acima_60 = 'acima_60';

    public static function getFaixaEtaria()
    {
        return [
            self::todas,
            self::menor_12,
            self::acima_12,
            self::acima_60,
        ];
    }

    public static function getFaixaEtariaTexto($texto)
    {
        $dados = [
            self::todas => "Todas as idades",
            self::menor_12 => "Menores de 12 anos",
            self::acima_12 => "Acima de 12 anos",
            self::acima_60 => "Acima de 60 anos",
        ];

        return $dados[$texto];
    }

    public function prestadores()
    {
        return $this->belongsTo(InstituicoesPrestadores::class, 'instituicoes_prestadores_id','id');
    }

    public function procedimentos()
    {
        return $this->belongsTo(InstituicaoProcedimentos::class, 'procedimentos_instituicoes_id','id');
    }

    public function grupos()
    {
        return $this->belongsTo(GruposInstituicoes::class, 'grupos_instituicoes_id','id');
    }


    public function agendamentos()
    {
        return $this->hasMany(Agendamentos::class, 'instituicoes_agenda_id');
    }

    public function convenios()
    {
        return $this->belongsToMany(Convenio::class, 'instituicao_agenda_has_convenio', 'instituicao_agenda_id', 'convenio_id');
    }
}
