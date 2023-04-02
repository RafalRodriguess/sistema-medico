<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faturamento extends Model
{
    use TraitLogInstituicao;
    use SoftDeletes;

    protected $table = "faturamentos";

    protected $fillable = [
        'descricao',
        'tipo',
        'tipo_tiss',
    ];

    // tipos
    const real = "real";
    const ch = "ch";
    const cbhpm_tipo = 'cbhpm';

    //tipos tiss
    const amb_90 = 'amb_90';
    const amb_92 = 'amb_92';
    const amb_96 = 'amb_96';
    const amb_99 = 'amb_99';
    const brasindice = 'brasindice';
    const cbhpm = 'cbhpm';
    const ciefas_93 = 'ciefas_93';
    const ciefas_2000 = 'ciefas_2000';
    const rol_ans = 'rol_ans';
    const sia_sus = 'sia_sus';
    const sih_sus = 'sih_sus';
    const simpro = 'simpro';
    const tunep = 'tunep';
    const vrpo = 'vrpo';
    const intercambio_unico = 'intercambio_unico';
    const tuss = 'tuss';
    const tuss_odontologico = 'tuss_odontologico';
    const tuss_taxas = 'tuss_taxas';
    const tuss_materiais = 'tuss_materiais';
    const tuss_medicamentos = 'tuss_medicamentos';
    const tuss_outras_especialidades = 'tuss_outras_especialidades';
    const propria_proc = 'propria_proc';
    const propria_pacote = 'propria_pacote';
    const tuss_provisoria = 'tuss_provisoria';
    const tuss_odonto = 'tuss_odonto';
    const tuss_prc_med = 'tuss_prc_med';
    const propria_procedimento = 'propria_procedimento';
    const propria_eou = 'propria_eou';
    const outras = 'outras';

    public static function tipoValor()
    {
        return [
            self::real => "real",
            self::ch => "ch",
            self::cbhpm_tipo => 'cbhpm',
        ];
    }

    public static function tipoValorTexto($texto)
    {
        $dados = [
            self::real => "REAL",
            self::ch => "CH",
            self::cbhpm_tipo => 'CBHPM',
        ];

        return $dados[$texto];
    }
    
    public static function tipoTISSValor()
    {
        return [
            self::amb_90 => 'amb_90',
            self::amb_92 => 'amb_92',
            self::amb_96 => 'amb_96',
            self::amb_99 => 'amb_99',
            self::brasindice => 'brasindice',
            self::cbhpm => 'cbhpm',
            self::ciefas_93 =>'ciefas_93',
            self::ciefas_2000 => 'ciefas_2000',
            self::rol_ans => 'rol_ans',
            self::sia_sus => 'sia_sus',
            self::sih_sus => 'sih_sus',
            self::simpro => 'simpro',
            self::tunep => 'tunep',
            self::vrpo => 'vrpo',
            self::intercambio_unico => 'intercambio_unico',
            self::tuss => 'tuss',
            self::tuss_odontologico => 'tuss_odontologico',
            self::tuss_taxas => 'tuss_taxas',
            self::tuss_materiais => 'tuss_materiais',
            self::tuss_medicamentos => 'tuss_medicamentos',
            self::tuss_outras_especialidades => 'tuss_outras_especialidades',
            self::propria_proc => 'propria_proc',
            self::propria_pacote => 'propria_pacote',
            self::tuss_provisoria => 'tuss_provisoria',
            self::tuss_odonto => 'tuss_odonto',
            self::tuss_prc_med => 'tuss_prc_med',
            self::propria_procedimento => 'propria_procedimento',
            self::propria_eou => 'propria_eou',
            self::outras => 'outras',
        ];
    }

    public static function tipoTISSValorTexto($texto)
    {
        $dados = [
            self::amb_90 => 'AMB 90',
            self::amb_92 => 'AMB 92',
            self::amb_96 => 'AMB 96',
            self::amb_99 => 'AMB 99',
            self::brasindice => 'BRASINDICE',
            self::cbhpm => 'CBHPM',
            self::ciefas_93 =>'CIEFAS 93',
            self::ciefas_2000 => 'CIEFAS 200',
            self::rol_ans => 'ROL ANS',
            self::sia_sus => 'SIA SUS',
            self::sih_sus => 'SIH SUS',
            self::simpro => 'SIMPRO',
            self::tunep => 'TUNEP',
            self::vrpo => 'VRPO',
            self::intercambio_unico => 'INTERCAMBIO UNICO',
            self::tuss => 'TUSS',
            self::tuss_odontologico => 'TUSS ODONTOLOGICO',
            self::tuss_taxas => 'TUSS TAXAS',
            self::tuss_materiais => 'TUSS MATERIAIS',
            self::tuss_medicamentos => 'TUSS MEDICAMENTOS',
            self::tuss_outras_especialidades => 'TUSS OUTRAS ESPECIALIDADES',
            self::propria_proc => 'PROPRIA PROC',
            self::propria_pacote => 'PROPRIA PACOTE',
            self::tuss_provisoria => 'TUSS PROVISORIA',
            self::tuss_odonto => 'TUSS ODONTO',
            self::tuss_prc_med => 'TUSS PRC MED',
            self::propria_procedimento => 'PROPRIA PROCEDIMENTO',
            self::propria_eou => 'PROPRIA E/OU',
            self::outras => 'OUTRAS',
        ];

        return $dados[$texto];
    }

    public function procedimentos()
    {
        return $this->hasMany(FaturamentoProcedimento::class, 'faturamento_id');
    }

    public function scopeSearch(Builder $query, $search = ""):Builder
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
