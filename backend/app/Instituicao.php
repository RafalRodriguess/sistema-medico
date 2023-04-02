<?php

namespace App;

use App\Integracoes\Factory;
use App\Integracoes\Integracao;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Atendimento;
use App\Setor;
use App\CentroCusto;
use App\TipoDocumento;
use App\Http\Controllers\Instituicao\ModalidadesExame;
use App\Http\Controllers\Instituicao\SetoresExame;
use App\UnidadeInternacao;
use App\Origem;
use App\MotivoCancelamentoAlta;
use Illuminate\Support\Facades\Gate;

use function Clue\StreamFilter\fun;

class Instituicao extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'instituicoes';

    protected $fillable = [
        'id',
        'nome',
        'chave_unica',
        'metadados',
        'habilitado',
        'imagem',
        'email',
        'telefone',
        'rua',
        'numero',
        'cep',
        'bairro',
        'cidade',
        'estado',
        'complemento',
        'max_parcela',
        'free_parcela',
        'valor_parcela',
        'taxa_tectotum',
        'valor_minimo',
        'cartao_entrega',
        'sincronizacao_agenda',
        'dinheiro',
        'razao_social',
        'cnpj',
        'inscricao_estadual',
        'inscricao_municipal',
        'cnes',
        'tipo',
        'ramo_id',
        'finalizar_consultorio',
        'possui_faturamento_sancoop',
        'config',
        'id_enotas',
        'enviar_pesquisa_satisfacao_atendimentos',
        'automacao_whatsapp',
        'sancoop_automacao_envio_guias',
        'apibb_possui',
        'apibb_codigo_cedente',
        'apibb_indicador_pix',
        'apibb_client_id',
        'apibb_client_secret',
        'apibb_gw_dev_app_key',
        'automacao_whatsapp_regra_envio',
        'kentro_fila_empresa',
        'kentro_msg_confirmacao',
        'kentro_msg_resposta_confirmacao',
        'kentro_msg_resposta_remarcacao',
        'kentro_msg_pesquisa_satisfacao',
        'kentro_msg_resposta_pesquisa_satisfacao',
        'kentro_msg_aniversario',
        'automacao_whatsapp_horario_agenda_prestador',
        'ausente_agenda',
        'p_juros',
        'p_multa',
        'dias_pagamento',
        'desconto_por_procedimento_agenda',
        'possui_convenio_terceiros',
        'codigo_acesso_terceiros',
        'telemedicina_integrado',
        'automacao_whatsapp_botoes',
        'automacao_whatsapp_aniversario',
        'integracao_asaplan',
    ];

    protected $casts = [
        'habilitado' => 'boolean',
        'enviar_pesquisa_satisfacao_atendimentos' => 'boolean',
        'automacao_whatsapp' => 'boolean',
        'ausente_agenda' => 'boolean',
        'desconto_por_procedimento_agenda' => 'boolean',
        'possui_convenio_terceiros' => 'boolean',
        'telemedicina_integrado' => 'boolean',
        'automacao_whatsapp_botoes' => 'boolean',
        'automacao_whatsapp_aniversario' => 'boolean',
        'integracao_asaplan' => 'boolean',
    ];

    protected $tipos = [
        'matriz' => [
            'id' => 1,
            'valor' => 'Matriz'
        ],
        'filial' => [
            'id' => 2,
            'valor' => 'Filial'
        ]
    ];

    const hospital = 1;
    const clinica = 2;
    const posto_saude = 3;
    const outro = 4;

    const matriz = 1;
    const filial = 2;

    protected $appends = ['imagem_300px', 'imagem_200px', 'imagem_100px'];

    public function motivoBaixa()
    {
        return $this->hasMany(MotivoBaixa::class, 'instituicao_id');
    }

    public function atividadesMedicas()
    {
        return $this->hasMany(AtividadeMedica::class, 'instituicao_id');
    }

    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class, 'instituicao_id');
    }

    public function escalasMedicas()
    {
        return $this->hasMany(EscalaMedica::class, 'instituicao_id');
    }

    public function setores()
    {
        return $this->hasMany(Setor::class, 'instituicao_id');
    }

    public function instituicaoPessoas()
    {
        return $this->hasMany(Pessoa::class, 'instituicao_id');
    }

    public function pacientes()
    {
        return $this->hasMany(Pessoa::class, 'instituicao_id')->where('tipo', 2);
    }

    public function fornecedores()
    {
        return $this->hasMany(Pessoa::class, 'instituicao_id')->where('tipo', 3);
    }

    public function centrosCustos()
    {
        return $this->hasMany(CentroCusto::class, 'instituicao_id');
    }

    public function unidadesInternacoes()
    {
        return $this->hasMany(UnidadeInternacao::class, 'instituicao_id');
    }

    public function origens()
    {
        return $this->hasMany(Origem::class, 'instituicao_id');
    }

    public function motivosAltas()
    {
        return $this->hasMany(MotivoAlta::class, 'instituicao_id');
    }

    public function motivosCancelamentoAltas()
    {
        return $this->hasMany(MotivoCancelamentoAlta::class, 'instituicao_id');
    }

    public function centrosCirurgicos()
    {
        return $this->hasMany(CentroCirurgico::class, 'instituicao_id');
    }

    public function tipoPartos()
    {
        return $this->hasMany(TipoParto::class, 'instituicao_id');
    }

    public function unidades()
    {
        return $this->hasMany(Unidade::class, 'instituicao_id');
    }

    public function motivoPartos()
    {
        return $this->hasMany(MotivoParto::class, 'instituicao_id');
    }

    public function tipoAnestesia()
    {
        return $this->hasMany(TipoAnestesia::class, 'instituicao_id');
    }

    public function tipoCompras()
    {
        return $this->hasMany(TipoCompra::class, 'instituicao_id');
    }

    public function solicitacaoCompras()
    {  
        return $this->hasMany(InstituicaoSolicitacaoCompra::class, 'instituicao_id');
    }

    public function compradores()
    {
        return $this->hasMany(Comprador::class, 'instituicao_id');
    }

    public function estoques()
    {
        return $this->hasMany(Estoque::class, 'instituicao_id');
    }

    public function motivosMortesRN()
    {
        return $this->hasMany(MotivoMorteRN::class, 'instituicao_id');
    }

    public function classes()
    {
        return $this->hasMany(Classe::class, 'instituicao_id');
    }

    public function especies()
    {
        return $this->hasMany(Especie::class, 'instituicao_id');
    }

    public function formasPagamentos()
    {
        return $this->hasMany(FormaPagamento::class, 'instituicao_id');
    }

    public function tiposDocumentos()
    {
        return $this->hasMany(TipoDocumento::class, 'instituicao_id');
    }

    public function motivo_cancelamentos()
    {
        return $this->hasMany(MotivoCancelamento::class, 'instituicao_id');
    }

    public function motivo_pedidos()
    {
        return $this->hasMany(MotivoPedido::class, 'instituicao_id');
    }

    public function contas()
    {
        return $this->hasMany(Conta::class, 'instituicao_id');
    }

    public function produtos()
    {
        return $this->hasMany(Produto::class, 'instituicao_id');
    }

    public function cartoesCredito()
    {
        return $this->hasMany(CartaoCredito::class, 'instituicao_id');
    }

    public function planosContas()
    {
        return $this->hasMany(PlanoConta::class, 'instituicao_id');
    }

    public function medicamentos()
    {
        return $this->hasMany(InstituicaoMedicamento::class, 'instituicao_id')->where("status", 1);
    }

    public function medicamentosPesquisa()
    {
        return $this->hasMany(InstituicaoMedicamento::class, 'instituicao_id');
    }

    public function medicamentosInstituicoes()
    {
        return $this->hasMany(Medicamento::class, 'instituicao_id');
    }

    public static function getRamos()
    {
        return [
            self::hospital,
            self::clinica,
            self::posto_saude,
            self::outro
        ];
    }

    public static function getTipos()
    {
        return [
            self::matriz,
            self::filial
        ];
    }


    public static function getRamoText($value)
    {
        $dados = [
            self::hospital => 'Hospital',
            self::clinica => 'Clínica',
            self::posto_saude => 'Posto de Saúde',
            self::outro => 'Outros'
        ];

        return $dados[$value];
    }

    public static function getTipoText($value)
    {
        $dados = [
            self::matriz => 'Matriz',
            self::filial => 'Filial'
        ];

        return $dados[$value];
    }

    public function getImagem300pxAttribute()
    {
        if (is_null($this->imagem) || empty($this->imagem)) {
            return null;
        } else {
            $caminho = Str::of($this->imagem)->explode('/');

            return $caminho[0] . '/' . $caminho[1] . '/300px-' . $caminho[2];
        }
    }

    public function getImagem200pxAttribute()
    {
        if (is_null($this->imagem) || empty($this->imagem)) {
            return null;
        } else {
            $caminho = Str::of($this->imagem)->explode('/');

            return $caminho[0] . '/' . $caminho[1] . '/200px-' . $caminho[2];
        }
    }

    public function documentos()
    {
        return $this->hasMany(DocumentoPrestador::class, 'intituicao_id');
    }

    public function getImagem100pxAttribute()
    {
        if (is_null($this->imagem) || empty($this->imagem)) {
            return null;
        } else {
            $caminho = Str::of($this->imagem)->explode('/');

            return $caminho[0] . '/' . $caminho[1] . '/100px-' . $caminho[2];
        }
    }
    public function banco()
    {
        return $this->belongsTo(ContaBancaria::class, 'banco_id');
    }

    public function acomodacoes()
    {
        return $this->hasMany(Acomodacao::class, 'instituicao_id');
    }

    public function medicos()
    {
        $instituicao = $this->id;
        return Prestador::query()
            ->whereHas('prestadoresInstituicoes', function ($query) use ($instituicao) {
                $query->where('instituicoes_id', $instituicao)->where('tipo', 2);
            });
    }
    
    public function prestadoresQtd()
    {
        $instituicao = $this->id;
        return Prestador::query()
            ->whereHas('prestadoresInstituicoes', function ($query) use ($instituicao) {
                $query->where('instituicoes_id', $instituicao)->whereIn('tipo', [2, 3, 6, 7, 8, 9, 10, 15])
                ->where('ativo', 1);
            });
    }

    public function medicosRelatorioAtendimentos()
    {
        $usuario_logado = request()->user('instituicao');
        // $usuario_prestador = $usuario_logado->prestadorMedico()->get();
        $instituicao = $this->id;

        $instituicao_logada = $usuario_logado->instituicao->where('id', $instituicao)->first();
        $prestadoresIds = explode(',', $instituicao_logada->pivot->visualizar_prestador);

        return Prestador::query()
            ->whereHas('prestadoresInstituicoes', function ($query) use ($instituicao, $usuario_logado, $prestadoresIds) {
                $query->where('instituicoes_id', $instituicao)->where('tipo', 2);
                if(!Gate::allows('habilidade_instituicao_sessao', 'visualizar_all_agenda_prestador')){
                    // if(count($usuario_prestador) > 0 && $usuario_prestador[0]->tipo == 2){
                        $query->where('instituicao_usuario_id', $usuario_logado->id);
                    // }
                }else{
                    if(!in_array('', $prestadoresIds)){
                        $query->whereIn('id', $prestadoresIds);
                    }
                }
            });
    }

    public function estoqueBaixa()
    {
        return $this->hasMany(EstoqueBaixa::class, 'instituicao_id');
    }
    public function estoqueEntrada()
    {
        return $this->hasMany(EstoqueEntradas::class, 'instituicao_id');
    }

    public function estoqueInventario()
    {
        return $this->hasMany(EstoqueInventario::class, 'instituicao_id');
    }

    public function tipoDocumento()
    {
        return $this->hasMany(TipoDocumento::class, 'instituicao_id');
    }
    public function pessoa()
    {
        return $this->hasMany(Pessoa::class, 'instituicao_id');
    }

    public function instituicaoUsuarios()
    {
        return $this->belongsToMany(InstituicaoUsuario::class, 'instituicao_has_usuarios', 'instituicao_id', 'usuario_id')->withPivot(['status', 'perfil_id', 'visualizar_prestador', 'visualizar_setores', 'desconto_maximo']);
    }

    public function prestadoresEspecialidades()
    {
        return $this->hasMany(InstituicoesPrestadores::class, 'instituicoes_id');
    }

    public function prestadores()
    {
        return $this->hasMany(InstituicoesPrestadores::class, 'instituicoes_id');

        // return $this->belongsToMany(Prestador::class, 'instituicoes_prestadores', 'instituicoes_id', 'prestadores_id')->whereNull('instituicoes_prestadores.deleted_at');
    }

    public function procedimentos()
    {
        return $this->belongsToMany(Procedimento::class, 'procedimentos_instituicoes', 'instituicoes_id', 'procedimentos_id')->whereNull('procedimentos_instituicoes.deleted_at');
    }


    public function procedimentosInstituicoes()
    {
        return $this->hasMany(InstituicaoProcedimentos::class, 'instituicoes_id')->whereNull('procedimentos_instituicoes.deleted_at');
    }
    public function instituicoesTransferencia()
    {
        return $this->hasMany(InstituicaoTransferencia::class, 'instituicao_id')
            ->whereNull('instituicoes_transferencia.deleted_at');
    }

    public function especialidades()
    {
        return $this->belongsToMany(Especialidade::class, 'inst_prest_especialidades', 'instituicao_prestador_id', 'especialidade_id');
    }

    public function especialidadesInstituicao()
    {
        return $this->hasMany(Especialidade::class, 'instituicoes_id');
    }

    public function equipesCirurgicas()
    {
        return $this->hasMany(EquipeCirurgica::class, 'instituicao_id');
    }

    public function instituicaoPaciente()
    {
        return $this->belongsToMany(Usuario::class, 'instituicao_has_pacientes', 'instituicao_id', 'usuario_id')
            ->using(InstituicaoPaciente::class)
            ->withPivot('metadados', 'id_externo', 'id');
    }

    public function getInstituicaoPaciente()
    {
        return $this->hasMany(InstituicaoPaciente::class, 'instituicao_id');
    }

    public function horarioFuncionamento()
    {
        return $this->hasMany(HorarioFuncionamentoInstituicao::class, 'instituicao_id');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if (empty($search)) {
            return $query;
        }

        if (preg_match('/^\d+$/', $search)) {
            return $query->where('id', 'like', "{$search}%");
        }

        return $query->where('nome', 'like', "%{$search}%");
    }


    public function integracao(): Integracao
    {
        return Factory::make($this);
    }

    public function modalidadesExame()
    {
        return $this->hasMany(ModalidadeExame::class, 'instituicao_id');
    }

    public function setoresExame()
    {
        return $this->hasMany(SetorExame::class, 'instituicao_id');
    }

    public function totens() {
        return $this->hasMany(Totem::class, 'instituicoes_id');
    }

    public function filasTriagem() {
        return $this->hasMany(FilaTriagem::class, 'instituicoes_id');
    }

    public function classificacoesTriagem()
    {
        return $this->hasMany(ClassificacaoTriagem::class, 'instituicoes_id');
    }

    public function preInternacoes()
    {
        return $this->hasMany(PreInternacao::class, 'instituicao_id')->where('pre_internacao', 1);
    }

    public function processosTriagem()
    {
        return $this->hasMany(ProcessoTriagem::class, 'instituicoes_id');
    }

    public function gruposCirurgias()
    {
        return $this->hasMany(GrupoCirurgia::class, 'instituicao_id');
    }

    public function cirurgias()
    {
        return $this->hasMany(Cirurgia::class, 'instituicao_id');
    }

    public function ViasAcesso()
    {
        return $this->hasMany(ViaAcesso::class, 'instituicao_id');
    }

    public function Equipamentos()
    {
        return $this->hasMany(Equipamento::class, 'instituicao_id');
    }

    public function contasPagar()
    {
        return $this->hasMany(ContaPagar::class, 'instituicao_id');
    }

    public function contasReceber()
    {
        return $this->hasMany(ContaReceber::class, 'instituicao_id');
    }

    public function solicitacoesEstoque()
    {
        return $this->hasMany(SolicitacaoEstoque::class, 'instituicoes_id');
    }

    public function solicitacoesCompras()
    {
        return $this->hasMany(SolicitacaoCompras::class, 'instituicoes_id');
    }

    public function internacoes()
    {
        return $this->hasMany(Internacao::class, 'instituicao_id')->where('pre_internacao', 0);
    }

    public function convenios()
    {
        return $this->hasMany(Convenio::class, 'instituicao_id');
    }

    public function apresentacaoConvenios()
    {
        return $this->hasMany(ApresentacaoConvenio::class, 'instituicao_id');
    }

    public function caixasCirurgicos()
    {
        return $this->hasMany(CaixaCirurgico::class, 'instituicao_id');
    }

    public function sanguesDerivados()
    {
        return $this->hasMany(SangueDerivado::class, 'instituicao_id');
    }

    public function motivosDivergencia()
    {
        return $this->hasMany(MotivoDivergencia::class, 'instituicoes_id');
    }

    public function estoqueEntradasProdutos()
    {
        return $this->hasManyThrough(EstoqueEntradaProdutos::class, EstoqueEntradas::class, 'instituicao_id', 'id_entrada');
    }

    public function tiposChamadaTotem()
    {
        return $this->hasMany(TipoChamadaTotem::class, 'instituicoes_id');
    }

    public function paineisTotem()
    {
        return $this->hasMany(PainelTotem::class, 'instituicoes_id');
    }

    public function especializacoes()
    {
        return $this->hasMany(Especializacao::class, 'instituicoes_id');
    }

    public function grupoProcedimentos()
    {
        return $this->hasMany(GruposProcedimentos::class, 'instituicao_id');
    }

    public function pacoteProcedimentos()
    {
        return $this->hasMany(PacotePrcedimento::class, 'instituicao_id');
    }

    public function prontuarioConfiguracao()
    {
        return $this->hasMany(ProntuarioConfiguracao::class, 'instituicao_id');
    }

    public function agendasAusente()
    {
        return $this->hasMany(AgendaAusente::class, 'instituicao_id');
    }

    public function faturamento_lotes()
    {
        return $this->hasMany(FaturamentoLote::class, 'instituicao_id');
    }

    public function configuracaoFiscal()
    {
        return $this->hasMany(ConfiguracaoFiscal::class, 'instituicao_id');
    }

    public function notasFiscais()
    {
        return $this->hasMany(NotaFiscal::class, 'instituicao_id');
    }

    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class, 'instituicao_id');
    }

    public function solicitantes()
    {
        return $this->hasMany(PrestadorSolicitante::class, 'instituicao_id');
    }

    public function saidasEstoque()
    {
        return $this->hasMany(SaidaEstoque::class, 'instituicoes_id');
    }

    public function gruposFaturamento()
    {
        return $this->hasMany(GrupoFaturamento::class, 'instituicao_id');
    }

    public function faturamentos()
    {
        return $this->hasMany(Faturamento::class, 'instituicao_id');
    }

    public function regrasCobranca()
    {
        return $this->hasMany(RegraCobranca::class, 'instituicao_id');
    }

    public function procedimentoAtendimentos()
    {
        return $this->hasMany(ProcedimentoAtendimento::class, 'instituicao_id');
    }

    public function modelosRecibo()
    {
        return $this->hasMany(ModeloRecibo::class, 'instituicao_id');
    }

    public function modeloArquivos()
    {
        return $this->hasMany(ModeloArquivo::class, 'instituicao_id');
    }

    public function vinculoTuss()
    {
        return $this->hasMany(VinculoTuss::class, 'instituicao_id');
    }

    public function maquinasCartao()
    {
        return $this->hasMany(MaquinaCartao::class, 'instituicao_id');
    }

    public function compromissos()
    {
        return $this->hasMany(Compromisso::class, 'instituicao_id');
    }
    
    public function atendimentoPaciente()
    {
        return $this->hasMany(AtendimentoPaciente::class, 'instituicao_id');
    }

    public function motivoAtendimento()
    {
        return $this->hasMany(MotivoAtendimento::class, 'instituicao_id');
    }

    public function locaisEntregaExame() {
        return $this->hasMany(LocalEntregaExame::class, 'instituicao_id');
    }

    public function entregasExame() {
        return $this->hasManyThrough(EntregaExame::class, LocalEntregaExame::class, 'instituicao_id', 'local_entrega_id', 'id', 'id');
    }

    public function motivosConclusao()
    {
        return $this->hasMany(MotivoConclusao::class, 'instituicao_id');
    }

    public function motivosConclusaoView()
    {
        return $this->hasMany(MotivoConclusao::class, 'instituicao_id')->withTrashed();
    }

    public function procedimentosSus()
    {
        return $this->hasMany(ProcedimentoSus::class, 'instituicoes_id');
    }

    public function vinculosProcedimentosSus()
    {
        return $this->hasMany(VinculoSUS::class, 'id_instituicao');
    }

    public function atendimentosUrgencia()
    {
        return $this->hasMany(AgendamentoAtendimentoUrgencia::class, 'instituicao_id');
    }
    
    public function agendamentosListaEspera()
    {
        return $this->hasMany(AgendamentoListaEspera::class, 'instituicao_id');
    }
   
    public function modelosTermoFolhaSala()
    {
        return $this->hasMany(ModeloTermoFolhaSala::class, 'instituicao_id');
    }

    public function logInstituicao()
    {
        return $this->hasMany(LogInstituicao::class, 'instituicao_id');
    }

    public function scopeGetTotalBoletosInstituicoes(Builder $query, $dados): Builder
    {
        $query->whereIn('id', $dados['instituicoes']);

        $query->whereHas('contasReceber', function($q) use($dados){
            $q->whereNotNull('apibb_codigo_barra_numerico');
            $q->whereBetween('created_at', [date('Y-m-d H:i:s', strtotime($dados['data_inicio'].' 00:00:00')), date('Y-m-d H:i:s', strtotime($dados['data_fim'].' 23:59:59'))]);
        });
        
        $query->with(['contasReceber' => function($q) use($dados){
            $q->whereNotNull('apibb_codigo_barra_numerico');
            $q->whereBetween('created_at', [date('Y-m-d H:i:s', strtotime($dados['data_inicio'].' 00:00:00')), date('Y-m-d H:i:s', strtotime($dados['data_fim'].' 23:59:59'))]);
        }]);

        return $query;
    }
}
