<?php

namespace App;

use App\Support\ModelPossuiLogs;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Procedimento extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;
    // use TraitLogInstituicao;

    protected $table = 'procedimentos';

    protected $fillable = [
        'id',
        'descricao',
        'tipo',
        'odontologico',
        'possui_regiao',
        'sexo',
        'pacote',
        'qtd_maxima',
        'tipo_servico',
        'tipo_consulta',
        'recalcular',
        'busca_ativa',
        'parto',
        'diaria_uti_rn',
        'md_mt',
        'cod',
        'pesquisa_satisfacao',
        'exige_quantidade',
        'valor_custo',
        'n_cobrar_agendamento',
        'vinculo_tuss_id',
        'duracao_atendimento',
        'tipo_guia',
        'compromisso_id',
        'tipo_limpeza',
    ];

    protected $casts = [
        'odontologico' => 'boolean',
        'possui_regiao' => 'boolean',
        'pacote' => 'boolean',
        'recalcular' => 'boolean',
        'busca_ativa' => 'boolean',
        'parto' => 'boolean',
        'diaria_uti_rn' => 'boolean',
        'md_mt' => 'boolean',
        'pesquisa_satisfacao' => 'boolean',
        'exige_quantidade' => 'boolean',
        'n_cobrar_agendamento' => 'boolean',
        'tipo_limpeza' => 'boolean',
    ];

    const diaria = 'diaria';
    const diaria_uti = 'diaria_uti';
    const diaria_acompanhante = 'diaria_acompanhante';
    const taxa_sala = 'taxa_sala';
    const taxa_gases_medicinais = 'taxa_gases_medicinais';
    const taxa_equipamentos = 'taxa_equipamentos';
    const taxa_plantao = 'taxa_plantao';
    const taxa_alugueis = 'taxa_alugueis';

    public static function getServicoHospitalares()
    {
        return [
            self::diaria => 'diaria',
            self::diaria_uti => 'diaria_uti',
            self::diaria_acompanhante => 'diaria_acompanhante',
            self::taxa_sala => 'taxa_sala',
            self::taxa_gases_medicinais => 'taxa_gases_medicinais',
            self::taxa_equipamentos => 'taxa_equipamentos',
            self::taxa_plantao => 'taxa_plantao',
            self::taxa_alugueis => 'taxa_alugueis',
        ];
    }

    public static function getServicoHospitalaresTexto($texto)
    {
        $dados = [
            self::diaria => 'Diária',
            self::diaria_uti => 'Diária de U.T.I.',
            self::diaria_acompanhante => 'Diária de Acompanhante',
            self::taxa_sala => 'Taxa de Sala',
            self::taxa_gases_medicinais => 'Taxa de Gases Medicinais',
            self::taxa_equipamentos => 'Taxa de Equipamentos',
            self::taxa_plantao => 'Taxa de Plantão',
            self::taxa_alugueis => 'Taxa de Alugueis',
        ];

        return $dados[$texto];
    }

    public function procedimentoInstituicao()
    {
        return $this->hasMany(InstituicaoProcedimentos::class, 'procedimentos_id');
    }

    public function procedimentoInstituicaoId()
    {
        return $this->hasOne(InstituicaoProcedimentos::class, 'procedimentos_id')->where('instituicoes_id', request()->session()->get('instituicao'));
    }

    public function procedimentoInstituicaoOdontologico()
    {
        return $this->hasMany(InstituicaoProcedimentos::class, 'procedimentos_id')->where('instituicoes_id', request()->session()->get('instituicao'));
    }

    public function instituicoes()
    {
        return $this->belongsToMany(Instituicao::class, 'procedimentos_instituicoes', 'procedimentos_id', 'instituicoes_id')->whereNull('procedimentos_instituicoes.deleted_at');
    }

    public function ConveniosInstituicaoPrestadores()
    {
        return $this->hasMany(ProcedimentosConveniosInstituicoesPrestadores::class, 'procedimentos_id');
    }

    public function procedimentoConvenioInstuicao()
    {
        return $this->belongsToMany(ConveniosProcedimentos::class, InstituicaoProcedimentos::class, 'procedimentos_id', 'convenios_id')->withPivot(['id'])->whereNull('procedimentos_instituicoes.deleted_at');
    }

    public function prestadorExcessoes()
    {
        return $this->belongsToMany(InstituicoesPrestadores::class, 'excessao_procedimentos_prestador', 'procedimento_id', 'prestador_faturado_id')->withPivot('prestador_id');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if (empty($search)) {
            return $query->orderBy('id', 'desc');
        }

        if (preg_match('/^\d+$/', $search)) {
            return $query->where('id', 'like', "{$search}%")->orderBy('id', 'desc');
        }

        return $query->where('descricao', 'like', "%{$search}%")->orderBy('id', 'desc');
    }


    public function scopeSearchByInstituicao(Builder $query, string $search = '',  int $instituicao): Builder
    {

        //$query->get()->toArray()

        // $query->join('procedimentos_instituicoes', 'procedimentos_instituicoes.procedimentos_id', '=', 'procedimentos.id')
        // ->join('grupos_procedimentos', 'procedimentos_instituicoes.grupo_id', '=', 'grupos_procedimentos.id')
        // ->select('procedimentos_instituicoes.id', 'grupos_procedimentos.nome', 'procedimentos.descricao', 'procedimentos.tipo');



        $query->whereHas('procedimentoInstituicao', function ($q) use ($instituicao) {
            $q->where('instituicoes_id', $instituicao);
        })
            ->with([
                'procedimentoInstituicao' => function ($q) use ($instituicao) {
                    $q->where('instituicoes_id', $instituicao);
                },
                'procedimentoInstituicao.grupoProcedimento'
            ]);

        if (empty($search)) {
            return $query->orderBy('id', 'desc');
        }

        if (preg_match('/^\d+$/', $search)) {
            return $query->where('id', 'like', "{$search}%")->orderBy('id', 'desc');
        }

        return $query->where('descricao', 'like', "%{$search}%")->orderBy('id', 'desc');
    }

    public function scopesearchConveniosInstituicaoPrestadores(Builder $query,  string $search = '', Int $prestador)
    {

        $query->whereHas('ConveniosInstituicaoPrestadores', function ($q) use ($prestador) {
            $q->where('instituicoes_prestadores_id', $prestador);
        });
    }

    public function motivosCancelamento() {
        return $this->hasMany(MotivoCancelamento::class, 'procedimento_id');
    }

    public function vinculoTuss() {
        return $this->belongsTo(VinculoTuss::class, 'vinculo_tuss_id');
    }

    public function scopeGetProcedimentoPesquisaModel(Builder $query, $descricao, $instituicao_id): Builder
    {
        $query->where('descricao', 'like', "%{$descricao}%");

        $query->with(['procedimentoInstituicao' => function($q) use($instituicao_id){
            $q->where('instituicoes_id', $instituicao_id);
        }, 'procedimentoInstituicao.instituicaoProcedimentosConvenios']);

        $query->whereHas('procedimentoInstituicao', function($q) use($instituicao_id){
            $q->where('instituicoes_id', $instituicao_id);
        });
        $query->whereHas('procedimentoInstituicao.instituicaoProcedimentosConvenios');

        return $query;
    }

    public function scopeGetProcedimentoOdontologicoPesquisaModel(Builder $query, $descricao, $instituicao_id): Builder
    {
        $query->where('descricao', 'like', "%{$descricao}%");

        $query->where('odontologico', 1);

        $query->with(['procedimentoInstituicao' => function($q) use($instituicao_id){
            $q->where('instituicoes_id', $instituicao_id);
        }, 'procedimentoInstituicao.instituicaoProcedimentosConvenios']);

        $query->whereHas('procedimentoInstituicao', function($q) use($instituicao_id){
            $q->where('instituicoes_id', $instituicao_id);
        });
        $query->whereHas('procedimentoInstituicao.instituicaoProcedimentosConvenios');

        return $query;
    }

    public function scopeGetProcedimentoOdontologicoGrupoPesquisaModel(Builder $query, $descricao, $instituicao_id, $grupo): Builder
    {
        $query->where('descricao', 'like', "%{$descricao}%");

        $query->where('odontologico', 1);

        $query->with(['procedimentoInstituicao' => function($q) use($instituicao_id, $grupo){
            $q->where('instituicoes_id', $instituicao_id);
            $q->whereIn('grupo_id', $grupo);
        }, 'procedimentoInstituicao.instituicaoProcedimentosConvenios']);

        $query->whereHas('procedimentoInstituicao', function($q) use($instituicao_id, $grupo){
            $q->where('instituicoes_id', $instituicao_id);
            $q->whereIn('grupo_id', $grupo);
        });
        $query->whereHas('procedimentoInstituicao.instituicaoProcedimentosConvenios');

        return $query;
    }

    public function scopeGetProcedimentosDashboard(Builder $query, $data):Builder
    {
        // $query->selectRaw('descricao');

        $query->whereHas('procedimentoInstituicao', function($q) use($data){
            $q->where('instituicoes_id', request()->session()->get('instituicao'));
            $q->whereHas('conveniosProcedimentos.orcamentosItens', function($query) use($data){
                $query->whereDate('created_at', '>=', $data[0])
                ->whereDate('created_at', '<=', $data[1]);
            });
        });

        $query->with(['procedimentoInstituicao' => function($q) use($data){
            $q->where('instituicoes_id', request()->session()->get('instituicao'));
            $q->with(['conveniosProcedimentos.orcamentosItens' => function($query) use($data){
                $query->whereDate('created_at', '>=', $data[0])
                ->whereDate('created_at', '<=', $data[1]);
            }]);
        }]);


        return $query;
    }

    public function scopeGetProcedimentosRealizadosDashboard(Builder $query, $data):Builder
    {
        $query->whereHas('procedimentoInstituicao', function($q) use($data){
            $q->where('instituicoes_id', request()->session()->get('instituicao'));
            $q->whereHas('conveniosProcedimentos.orcamentosItens', function($query) use($data){
                $query->whereDate('data_conclusao', '>=', $data[0])
                ->whereDate('data_conclusao', '<=', $data[1]);
                $query->where('concluido', 1);
            });
        });

        $query->with(['procedimentoInstituicao' => function($q) use($data){
            $q->where('instituicoes_id', request()->session()->get('instituicao'));
            $q->with(['conveniosProcedimentos.orcamentosItens' => function($query) use($data){
                $query->whereDate('data_conclusao', '>=', $data[0])
                ->whereDate('data_conclusao', '<=', $data[1]);
                $query->where('concluido', 1);
            }, 'conveniosProcedimentos.convenios', 'grupoProcedimento']);
        }]);

        return $query;
    }

    public function scopeGetProcedimentosVendidosDashboard(Builder $query, $data):Builder
    {
        $query->whereHas('procedimentoInstituicao', function($q) use($data){
            $q->where('instituicoes_id', request()->session()->get('instituicao'));
            $q->whereHas('conveniosProcedimentos.orcamentosItens.odontologico', function($query) use($data){
                $query->whereDate('data_aprovacao', '>=', $data[0])
                ->whereDate('data_aprovacao', '<=', $data[1]);
            });
        });

        $query->with(['procedimentoInstituicao' => function($q) use($data){
            $q->where('instituicoes_id', request()->session()->get('instituicao'));
            $q->with(['conveniosProcedimentos.orcamentosItens.odontologico' => function($query) use($data){
                $query->whereDate('data_aprovacao', '>=', $data[0])
                ->whereDate('data_aprovacao', '<=', $data[1]);
            }, 'conveniosProcedimentos.convenios', 'grupoProcedimento']);
        }]);

        return $query;
    }

    public function scopeGetProcedimentoPesquisaVinculoConvenio(Builder $query, $descricao, $instituicao_id): Builder
    {
        $query->where('descricao', 'like', "%{$descricao}%");

        $query->with(['procedimentoInstituicao' => function($q) use($instituicao_id){
            $q->where('instituicoes_id', $instituicao_id);
        }]);

        $query->whereHas('procedimentoInstituicao', function($q) use($instituicao_id){
            $q->where('instituicoes_id', $instituicao_id);
        });

        return $query;
    }

    public function procedimentoVinculado()
    {
        return $this->hasOneThrough(ProcedimentoSus::class, VinculoSUS::class, 'id_procedimento', 'id', 'id', 'id_sus');
    }
}
