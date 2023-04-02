<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrupoFaturamento extends Model
{
    use TraitLogInstituicao;
    use SoftDeletes;

    protected $table = 'grupos_faturamento';

    protected $fillable = [
        'id',
        'instituicao_id',
        'descricao',
        'tipo',
        'ativo',
        'val_grupo_faturamento',
        'rateio_nf',
        'incide_iss',
    ];

    protected $casts = [
        'ativo' => 'boolean', 
        'val_grupo_faturamento' => 'boolean', 
        'rateio_nf' => 'boolean', 
        'incide_iss' => 'boolean', 
    ];

    const servicos_hospitalares = "servicos_hospitalares";
    const servicos_profissionais = 'servicos_profissionais';
    const servicos_diagnosticos = 'servicos_diagnosticos';
    const medicamentos = 'medicamentos';
    const materiais = 'materiais';
    const medicamentos_materiais = 'medicamentos_materiais';
    const orteses_proteses = 'orteses_proteses';
    const outros_lancamentos = 'outros_lancamentos';

    public static function tipoValores()
    {
        return [
            self::servicos_hospitalares => "servicos_hospitalares",
            self::servicos_profissionais => 'servicos_profissionais',
            self::servicos_diagnosticos => 'servicos_diagnosticos',
            self::medicamentos => 'medicamentos',
            self::materiais => 'materiais',
            self::medicamentos_materiais => 'medicamentos_materiais',
            self::orteses_proteses => 'orteses_proteses',
            self::outros_lancamentos => 'outros_lancamentos',
        ];
    }

    public static function tipoValoresTexto($texto)
    {
        $dados = [
            self::servicos_hospitalares => "Serviços Hospitalares",
            self::servicos_profissionais => 'Serviços Profissionais',
            self::servicos_diagnosticos => 'Serviços Diagnósticos',
            self::medicamentos => 'Medicamentos',
            self::materiais => 'Materiais',
            self::medicamentos_materiais => 'Medicamentos & Materiais',
            self::orteses_proteses => 'Orteses & Próteses',
            self::outros_lancamentos => 'Outros Lançamentos',
        ];

        return $dados[$texto];
    }
    public function scopeSearch(Builder $query, string $search = ''):Builder
    {
        if(empty($search))
        {
            return $query->orderBy('id', 'desc');
        }

        if(preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%")->orderBy('id', 'desc');
        }

        return $query->where('nome', 'like', "%{$search}%")->orderBy('id', 'desc');
    }
}
