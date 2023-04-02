<?php

namespace App;
use App\Support\ModelOverwrite;
use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use App\Casts\Checkbox;
use App\{
    ConveniosControleRetorno,
    ConveniosExcecoesRetorno,
};
use App\Support\TraitLogInstituicao;

class Convenio extends Model
{
    use ModelOverwrite;
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'convenios';

    protected $fillable = [
        'id',
        'versao_tiss_id',
        'nome',
        'descricao',
        'razao_social',
        'responsavel',
        'cargo_responsavel',
        'email',
        'dt_inicio_contrato',
        'endereco',
        'fone_contato',
        'imagem',
        // 2ª edição
        'apresentacoes_convenio_id',
        'email_glossas',
        'cep',
        'cgc',
        'inscricao_municipal',
        'inscricao_estadual',
        // 3ª edição
        'tipo_convenio',
        'categoria_obrigatoria',
        'guia_obrigatoria',
        'abate_devolucao',
        'filantropia',
        'fatura_p_alta',
        'desc_conta',
        // 4ª edição (aba complemento)
        'forma_agrupamento',
        'retorno_atendimento_ambulatorio',
        'retorno_atendimento_externo',
        'retorno_atendimento_urgencia',
        'fonte_de_remuneracao',
        'permitir_atendimento_ambulatorial',
        'permitir_atendimento_externo',
        'registro_ans',
        'carteira_pede',
        'carteira_verif_elig',
        'carteira_obg',
        'limite_contas_pre_remessa',
        'fechar_conta_amb_sem_impressao',
        'quantidade_alerta_faixa',
        'tipo_cobranca_oncologia',
        // 4ª Versão
        'pessoas_id',
        'instituicao_id',
        'ativo',
        'cnpj',
        'sancoop_cod_convenio',
        'sancoop_desc_convenio',
        'carteirinha_obg',
        'aut_obrigatoria',
        'divisao_tipo_guia',
        'possui_terceiros',
    ];

    // Fazendo cast nos getters
    protected $casts = [
        'categoria_obrigatoria' => Checkbox::class,
        'abate_devolucao' => Checkbox::class,
        'filantropia' => Checkbox::class,
        'fatura_p_alta' => Checkbox::class,
        'desc_conta' => Checkbox::class,
        'permitir_atendimento_ambulatorial' => Checkbox::class,
        'permitir_atendimento_externo' => Checkbox::class,
        'carteira_pede' => Checkbox::class,
        'carteira_verif_elig' => Checkbox::class,
        'carteira_obg' => Checkbox::class,
        'fechar_conta_amb_sem_impressao' => Checkbox::class,
        'carteirinha_obg' => 'boolean',
        'possui_terceiros' => 'boolean',
    ];

    // Models allowed to be overwritten
    protected $allowed_overwrite = [
        ConveniosControleRetorno::class,
        ConveniosExcecoesRetorno::class,
    ];

    // Enum de tipos de convenio
    const opcoes_tipo_convenio = [
        0 => 'SIA/SUS',
        1 => 'SIH/SUS',
        2 => 'Convênios',
        3 => 'Particular',
    ];

    // Enum de categoria obrigatória
    const opcoes_guia_obrigatoria = [
        0 => 'Atendimento',
        1 => 'Conta',
        2 => 'Não'
    ];

    // Enum de forma de agrupamento
    const opcoes_forma_agrupamento = [
        0 => 'Diário',
        1 => 'Importação',
        2 => 'Documento',
        3 => 'Nenhuma',
    ];

    // Enum de fonte de remuneracao
    const opcoes_fonte_de_remuneracao = [
        0 => 'Convênio - Plano privado',
        1 => 'Convênio - Plano público',
        2 => 'Particular pessoa física',
        3 => 'Gratúito',
        4 => 'Financiado com recurso própio da SES',
        5 => 'Financiado com recurso própio da SMS',
        6 => 'DPVAT',
        7 => 'Particular - Pessoa jurídica',
    ];

    // Enum de tipos de cobrança para oncologia
    const opcoes_tipo_cobranca_oncologia = [
        0 => 'Tratamento',
        1 => 'Ciclo',
        2 => 'Sessão'
    ];

    public function versaoTiss()
    {
        return $this->belongsTo(VersaoTiss::class, 'versao_tiss_id');
    }

    public function procedimentoConvenioInstuicao(){
        return $this->belongsToMany(InstituicaoProcedimentos::class, 'procedimentos_instituicoes_convenios', 'convenios_id', 'procedimentos_instituicoes_id')->withPivot(['id', 'valor'])->whereNull('procedimentos_instituicoes_convenios.deleted_at');
    }

    public function getProcedimentoConvenioInstuicao(){
        $instituicao_id = request()->session()->get('instituicao'); 
        
        return $this->belongsToMany(InstituicaoProcedimentos::class, 'procedimentos_instituicoes_convenios', 'convenios_id', 'procedimentos_instituicoes_id')->withPivot(['id', 'valor'])->whereNull('procedimentos_instituicoes_convenios.deleted_at')->where('instituicoes_id', $instituicao_id);
    }

    public function planos()
    {
        return $this->hasMany(ConvenioPlano::class, 'convenios_id');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
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


    public function scopeSearchConveniosInstituicao(Builder $query, string $search = '', Int $instituicao): Builder
    {

        // $query->whereHas('procedimentoConvenioInstuicao',function($q) use ($instituicao){
        //     $q->where('instituicoes_id',$instituicao);
        // });

        $query->where('instituicao_id', $instituicao);

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

    public function apresentacao()
    {
        return $this->belongsTo(ApresentacaoConvenio::class, 'apresentacoes_convenio_id');
    }

    public function controlesRetorno()
    {
        return $this->hasMany(ConveniosControleRetorno::class, 'convenios_id');
    }

    public function fornecedor()
    {
        return $this->belongsTo(Pessoas::class, 'pessoas_id');
    }

    public function excecoes()
    {
        return $this->hasMany(ConveniosExcecoesRetorno::class, 'convenios_id');
    }

    public function excecoesProcedimentos()
    {
        return $this->hasManyThrough(Procedimento::class, ConveniosExcecoesRetorno::class, 'convenios_id', 'id', 'id', 'procedimentos_id');
    }

    public function conveniosProcedimentos()
    {
        return $this->hasMany(ConveniosProcedimentos::class, 'convenios_id');
    }

    public function scopeGetConveniosDashboard(Builder $query, $data):Builder
    {
        $query->where('instituicao_id', request()->session()->get('instituicao'));

        $query->whereHas('conveniosProcedimentos.orcamentosItens', function($q) use($data){
            $q->whereDate('created_at', '>=', $data[0])
            ->whereDate('created_at', '<=', $data[1]);
        });
        
        $query->with(['conveniosProcedimentos.orcamentosItens'=> function($q) use($data){
            $q->whereDate('created_at', '>=', $data[0])
            ->whereDate('created_at', '<=', $data[1]);
        }]);

        return $query;
    }
}
