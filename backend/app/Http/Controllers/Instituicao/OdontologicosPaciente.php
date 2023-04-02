<?php

namespace App\Http\Controllers\Instituicao;

use Agendamento;
use App\Agendamentos;
use App\ContaPagar;
use App\ContaReceber;
use App\Convenio;
use App\ConveniosProcedimentos;
use App\GruposProcedimentos;
use App\Http\Controllers\Controller;
use App\Http\Requests\Odontologico\AlterarLaboratorioOdontologicoRequest;
use App\Http\Requests\Odontologico\AlterarNegociadorResponsavelRequest;
use App\Http\Requests\Odontologico\ConcluirProcedimentoOdontologicoRequest;
use App\Http\Requests\Odontologico\CriarOdontologicoProcedimentosRequest;
use App\Http\Requests\Odontologico\FinalizarOrcamentoFinanceiroRequest;
use App\Instituicao;
use App\OdontologicoItemPaciente;
use App\OdontologicoPaciente;
use App\Pessoa;
use App\RegiaoProcedimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\ConverteValor;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class OdontologicosPaciente extends Controller
{
    public function odontologicoPaciente(Request $request, $agendamento = null, Pessoa $paciente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_odontologico');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        // abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $grupos = GruposProcedimentos::where('instituicao_id', $instituicao->id)->with(['procedimentos_instituicoes.procedimento' => function($q) {
            $q->where('odontologico', 1);
        }])->whereHas('procedimentos_instituicoes.procedimento', function($q) {
            $q->where('odontologico', 1);
        })->get();

        $regioes = RegiaoProcedimento::get();

        $desconto_maximo = $user->instituicao()->where('instituicao_id', $instituicao->id)->first()->pivot->desconto_maximo;
        // $odontologicos = $paciente->odontologicos()->orderBy('created_at', 'DESC')->with('prestador')->get();
        // $odontologico = null;

        return view('instituicao.prontuarios.odontologicos.info', \compact('grupos', 'regioes', 'desconto_maximo'));
    }

    public function getProcedimentos(Request $request, GruposProcedimentos $grupo)
    {
        $instituicao = $request->session()->get('instituicao');

        abort_unless($instituicao === $grupo->instituicao_id, 403);

        $procedimentos = $grupo->procedimentos_instituicoes()->with(['procedimento' => function($q) {
            $q->where('odontologico', 1);
        }])->whereHas('procedimento', function($q) {
            $q->where('odontologico', 1);
        })->get();
        
        return response()->json($procedimentos);
    }

    public function odontologicoSalvar(CriarOdontologicoProcedimentosRequest $request, $agendamento = null, Pessoa $paciente)
    {
        $instituicao_id = $request->session()->get('instituicao');

        abort_unless($instituicao_id === $paciente->instituicao_id, 403);
        
        if($agendamento != 'null'){
            $agendamento = Agendamentos::find($agendamento);
            abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        }

        $dados = $request->validated();
        // dd($dados);
        DB::transaction(function() use($dados, $paciente, $agendamento, $instituicao_id, $request){
            $usuario_logado = $request->user('instituicao');

            $dadosOdontologico = [
                'paciente_id' => $paciente->id,
                'agendamento_id' => ($agendamento != 'null') ? $agendamento->id : null,
                'status' => 'criado',
                'avaliador_id' => $usuario_logado->id
            ];            

            $odontologico = OdontologicoPaciente::create($dadosOdontologico);
            $odontologico->criarLogCadastro($usuario_logado, $instituicao_id);

            $valor_total = 0;
            $dadosItensGeral = [];
            foreach ($dados['itens'] as $key => $value) {
                // $procedimentoConvenio = ConveniosProcedimentos::find($value['procedimento']);
                // $valor_total += $procedimentoConvenio->valor;
                
                $dadosItens = [
                    'status' => 'aprovado',
                    // 'valor' => $procedimentoConvenio->valor,
                    // 'valor_convenio' => $procedimentoConvenio->valor_convenio,
                    'dente_id' => $value['dente'],
                    'procedimento_id' => $value['procedimento'],
                    // 'regiao_procedimento_id' => (array_key_exists('regiao', $value)) ? $value['regiao'] : null,
                ];

                $item = $odontologico->itens()->create($dadosItens);
                $dadosItensGeral[] = $dadosItens;

                if(array_key_exists('regiao', $value)){
                    $dadosRegiao = explode(',',$value['regiao']);
                    $item->regiaoProcedimento()->attach($dadosRegiao);
                }
            }

            $odontologico->criarLog($usuario_logado, 'Itens do orçamento odontologico', $dadosItensGeral, $instituicao_id);
            // $odontologico->update(['valor_total' => $valor_total]);
        });
        
        $odontologicos = $paciente->odontologicos()->orderBy('created_at', 'DESC')->with('prestador')->get();

        return view('instituicao.prontuarios.odontologicos.orcamentos', \compact('odontologicos'));
    }

    public function odontologicoVisualizar(Request $request, Pessoa $paciente, OdontologicoPaciente $orcamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($orcamento->paciente_id === $paciente->id, 403);

        $orcamento->loadMissing('itens', 'itens.procedimentosItens', 'itens.procedimentosItens.procedimentoInstituicaoOdontologico', 'itens.procedimentosItens.procedimentoInstituicaoOdontologico.instituicaoProcedimentosConvenios', 'itens.regiao', 'itens.regiaoProcedimento');
        if($orcamento->status == 'aprovado'){
            $orcamento->loadMissing('contaReceber', 'contaReceber.contaCaixa', 'negociador', 'responsavel', 'itens.prestador');
            return view('instituicao.prontuarios.odontologicos.modal_visualizar_aprovado', \compact('orcamento'));
        }

        $convenios = Convenio::where('instituicao_id', $instituicao->id)->with(['getProcedimentoConvenioInstuicao' => function($q) {
            $q->whereHas('procedimento', function($query){
                $query->where('odontologico', 1);
            });
            $q->with(['procedimento' => function($query){
                $query->where('odontologico', 1);
            }]);
        }])->whereHas('getProcedimentoConvenioInstuicao.procedimento', function($q) {
            $q->where('odontologico', 1);
        })->get();

        $formaPagamento = ContaPagar::formas_pagamento();
        // $contas = $instituicao->contas()->get();
        $planosConta = $instituicao->planosContas()->where('padrao', 0)->get();
        $usuarios = $instituicao->instituicaoUsuarios()->get();
        $usuario_logado = $request->user('instituicao');
        $contas = $usuario_logado->contasInstituicao()->get();
        $maquinas_cartao = $instituicao->maquinasCartao()->get();

        return view('instituicao.prontuarios.odontologicos.modal_visualizar_criado', \compact('orcamento', 'formaPagamento', 'contas', 'planosConta', 'usuarios', 'convenios', 'maquinas_cartao'));
    }

    public function alterarNegociadorResponsavel(AlterarNegociadorResponsavelRequest $request, Pessoa $paciente ,OdontologicoPaciente $orcamento)
    {
        $instituicao = $request->session()->get('instituicao');

        abort_unless($instituicao === $paciente->instituicao_id, 403);
        abort_unless($orcamento->paciente_id === $paciente->id, 403);

        DB::transaction(function() use($instituicao, $request, $orcamento){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();

            $itens = explode(';',$dados['itens_alteracoes']);
            unset($dados['itens_alteracoes']);
            $itensIds = [];
            $valor_orcamento = 0;
            foreach ($itens as $key => $value) {
                $dadosIten = explode(',',$value);
                $itensIds[] = $dadosIten[0];
                $item = $orcamento->itens()->where('id', $dadosIten[0])->first();
                if(!empty($item)){
                    $procedimento = $item->procedimentosItens()->with(['procedimentoInstituicaoOdontologico.conveniosProcedimentos' => function($q) use($dadosIten){ 
                        $q->where('convenios_id', $dadosIten[1]);
                    }])->first();

                    $valor_orcamento += $procedimento->procedimentoInstituicaoOdontologico[0]->conveniosProcedimentos[0]->valor;

                    $dataIten = [
                        'procedimento_instituicao_convenio_id' => $procedimento->procedimentoInstituicaoOdontologico[0]->conveniosProcedimentos[0]->id,
                        'valor' => $procedimento->procedimentoInstituicaoOdontologico[0]->conveniosProcedimentos[0]->valor,
                        'valor_convenio' => $procedimento->procedimentoInstituicaoOdontologico[0]->conveniosProcedimentos[0]->valor_convenio,
                        'valor_custo' => $procedimento->valor_custo,
                        'desconto' => $dadosIten[2],
                        'laboratorio' => $dadosIten[3]
                    ];

                    $item->update($dataIten);
                    $item->criarLogEdicao($usuario_logado, $instituicao);
                }
            }

            $data = [
                'negociador_id' => array_key_exists('negociador_id_item', $dados) ? $dados['negociador_id_item'] : null,
                'responsavel_id' => array_key_exists('responsavel_id_item', $dados) ? $dados['responsavel_id_item'] : null,
                'valor_total' => $valor_orcamento
            ];

            $orcamento->update($data);
            $orcamento->criarLogEdicao($usuario_logado, $instituicao);
        });

        return response()->json('sucesso');
    }

    public function salvarOrcamentoFinanceiro(FinalizarOrcamentoFinanceiroRequest $request, Pessoa $paciente ,OdontologicoPaciente $orcamento)
    {
        $instituicao = $request->session()->get('instituicao');

        abort_unless($instituicao === $paciente->instituicao_id, 403);
        abort_unless($orcamento->paciente_id === $paciente->id, 403);

        $dados = $request->validated();
        unset($dados['pagamento']);

        $pagamentos = collect($request->pagamento)
            ->filter(function($pagamento){
                $valor = ConverteValor::converteDecimal($pagamento['valor']);
                return $valor > 0;
            })
            ->map(function($pagamento){
                return [
                    'valor_parcela' => ConverteValor::converteDecimal($pagamento['valor']),
                    'data_vencimento' => $pagamento['data'],
                    'conta_id' => $pagamento['conta_id'],
                    'plano_conta_id' => $pagamento['plano_conta_id'],
                    'forma_pagamento' => $pagamento['forma_pagamento'],
                    'valor_pago' => (array_key_exists('recebido', $pagamento)) ? ConverteValor::converteDecimal($pagamento['valor']) : null,
                    'data_pago' => (array_key_exists('recebido', $pagamento)) ? $pagamento['data'] : null,
                    'status' => (array_key_exists('recebido', $pagamento)) ? 1: 0,
                    'num_parcelas' => $pagamento['num_parcelas'],
                    'maquina_id' => (!empty($pagamento['maquina_id'])) ? $pagamento['maquina_id'] : null,
                    'taxa_cartao' => (!empty($pagamento['taxa'])) ? $pagamento['taxa'] : null,
                    'cod_aut' => (!empty($pagamento['cod_aut'])) ? $pagamento['cod_aut'] : null,
                ];
            });

        DB::transaction(function() use($pagamentos, $paciente, $orcamento, $instituicao, $request, $dados){
            $usuario_logado = $request->user('instituicao');

            $itens = explode(';',$dados['itens_aprovados']);
            $itensIds = [];
            $valor_orcamento = 0;
            $valor_aprovado = 0;

            foreach ($itens as $key => $value) {
                $dadosIten = explode(',',$value);
                $itensIds[] = $dadosIten[0];
                $item = $orcamento->itens()->where('id', $dadosIten[0])->first();
                if(!empty($item)){
                    $procedimento = $item->procedimentosItens()->with(['procedimentoInstituicaoOdontologico.conveniosProcedimentos' => function($q) use($dadosIten){ 
                        $q->where('convenios_id', $dadosIten[1]);
                    }])->first();

                    $desconto = (!empty($dadosIten[2])) ? $dadosIten[2] : 0;
                    $laboratorio = (!empty($dadosIten[3])) ? $dadosIten[3] : 0;

                    $valor_orcamento += $procedimento->procedimentoInstituicaoOdontologico[0]->conveniosProcedimentos[0]->valor;
                    $valor_aprovado +=  $procedimento->procedimentoInstituicaoOdontologico[0]->conveniosProcedimentos[0]->valor + $desconto;

                    $dataIten = [
                        'procedimento_instituicao_convenio_id' => $procedimento->procedimentoInstituicaoOdontologico[0]->conveniosProcedimentos[0]->id,
                        'valor' => $procedimento->procedimentoInstituicaoOdontologico[0]->conveniosProcedimentos[0]->valor,
                        'valor_convenio' => $procedimento->procedimentoInstituicaoOdontologico[0]->conveniosProcedimentos[0]->valor_convenio,
                        'valor_custo' => $procedimento->valor_custo,
                        'desconto' => $desconto,
                        'laboratorio' => $laboratorio,
                    ];
                    $item->update($dataIten);
                    $item->criarLogEdicao($usuario_logado, $instituicao);
                }
            }

            $orcamento->update(['valor_total' => $valor_orcamento]);

            $itensOrcamento = $orcamento->itens()->get();

            $orcamentoNovo = null;
            foreach ($itensOrcamento as $key => $value) {
                if(!in_array($value->id, $itensIds)){
                    
                    if($orcamentoNovo){
                        // $valorNovo = $orcamentoNovo->valor_total + $value->valor;
                        // $orcamentoNovo->update(['valor_total' => $valorNovo]);

                        $value->update(['odontologico_paciente_id' => $orcamentoNovo->id]);
                        $value->criarLogEdicao($usuario_logado, $instituicao);

                    }else{
                        $dadosNovoOrcamento = [
                            'paciente_id' => $paciente->id,
                            'agendamento_id' => $orcamento->agendamento_id,
                            'status' => 'reprovado',
                            // 'valor_total' =>  $value->valor,
                            'finalizado' => 0,
                            'data_reprovacao' => date('Y-m-d'),
                        ];

                        $orcamentoNovo = OdontologicoPaciente::create($dadosNovoOrcamento);
                        $orcamentoNovo->criarLogCadastro($usuario_logado, $instituicao);

                        $value->update(['odontologico_paciente_id' => $orcamentoNovo->id]);
                        $value->criarLogEdicao($usuario_logado, $instituicao);
                    }
                }
            }

            $itensOrcamento = $orcamento->itens()->get();

            $dadosOrcamento = [
                'status' => 'aprovado',
                'negociador_id' => $dados['negociador_id'],
                'responsavel_id' => $dados['responsavel_id'],
                'data_aprovacao' => date('Y-m-d'),
                'desconto' => array_key_exists('desconto', $dados) ? ConverteValor::converteDecimal($dados['desconto']) : 0.0,
                'valor_aprovado' => $valor_aprovado,
            ];

            $orcamento->update($dadosOrcamento);
            $orcamento->criarLogEdicao($usuario_logado, $instituicao);
            if($orcamento->valor_aprovado > 0){
                if(($orcamento->valor_aprovado - $orcamento->desconto) > 0){
                    foreach ($pagamentos as $key => $value) {
                        $idPai = 0;
                        $data = $value;
                        
                        //////CARTAO DE CREDITO
                        if($data['forma_pagamento'] == 'cartao_credito' OR $data['forma_pagamento'] == 'boleto_cobranca' ){

                            $valor_parcela = $data['valor_parcela']/$data['num_parcelas'];

                            $valor_parcela = number_format($valor_parcela, 2, '.', '');

                            for ($i=0; $i < $data['num_parcelas']; $i++) { 
                                $valor_parcela_utilizar = 0;
                                if($i == 0){
                                    $total_parcelas = $valor_parcela*$data['num_parcelas'];
                                    
                                    if($total_parcelas == $data['valor_parcela']){
                                        $valor_parcela_utilizar = $valor_parcela;
                                    }else if($total_parcelas > $data['valor_parcela']){
                                        $valor_parcela_utilizar = $total_parcelas - $data['valor_parcela'];
                                        $valor_parcela_utilizar = number_format($valor_parcela_utilizar, 2, '.', '');

                                        $valor_parcela_utilizar = $valor_parcela - $valor_parcela_utilizar;
                                    }else{
                                        $valor_parcela_utilizar = $data['valor_parcela'] - $total_parcelas;
                                        $valor_parcela_utilizar = number_format($valor_parcela_utilizar, 2, '.', '');
                                        $valor_parcela_utilizar = $valor_parcela + $valor_parcela_utilizar;
                                    }
                                    
                                    $data_vencimento =  $data['data_vencimento'];

                                }else{
                                    $valor_parcela_utilizar = $valor_parcela;
                                    $data_vencimento = date('Y-m-d', strtotime($data_vencimento.' +1 month'));
                                }
                                
                                // $m = $i + 1;
                                // if($data['forma_pagamento'] == 'cartao_credito'){
                                //     $data_vencimento = date('Y-m-d', strtotime($data['data_vencimento'].' +'.$m.' month'));
                                // }
                                
                                $dadosCartaoCredito = [
                                    'valor_parcela' => $valor_parcela_utilizar,
                                    'data_vencimento' => !empty($data_vencimento) ? $data_vencimento : date('Y-m-d', strtotime($data['data_vencimento'])),
                                    'conta_id' => $data['conta_id'],
                                    'plano_conta_id' => $data['plano_conta_id'],
                                    'forma_pagamento' => $data['forma_pagamento'],
                                    'num_parcela' => $i + 1,
                                    'descricao' => "Parcela de orçamento odontologico",
                                    'pessoa_id' => $paciente->id,
                                    'instituicao_id' => $instituicao,
                                    'valor_total' => $total_parcelas,
                                    'num_parcelas' => $data['num_parcelas'],
                                    "data_pago" => $data['data_pago'],
                                    "valor_pago" => $valor_parcela_utilizar,
                                    'tipo_parcelamento' => 'mensal',
                                    'status' => $data['status'],
                                    'maquina_id' => (!empty($data['maquina_id'])) ? $data['maquina_id'] : null,
                                    'taxa_cartao' => (!empty($data['taxa_cartao'])) ? number_format($data['taxa_cartao'] / $data['num_parcelas'], 2) : null,
                                    'cod_aut' => (!empty($data['cod_aut'])) ? $data['cod_aut'] : null,
                                ];

                                // if($data['forma_pagamento'] == 'boleto_cobranca'){
                                //     $data_vencimento = date('Y-m-d', strtotime($data['data_vencimento'].' +'.$m.' month'));
                                // }

                                $contaReceber = $orcamento->contaReceber()->create($dadosCartaoCredito);
                                $contaReceber->criarLogCadastro($usuario_logado, $instituicao);

                                if($idPai == 0){
                                    $idPai = $contaReceber->id;
                                    $contaReceber->update(['conta_pai' => $idPai]);
                                }else{
                                    $contaReceber->update(['conta_pai' => $idPai]);
                                }
                            }

                        }else{
                            unset($data['num_parcelas']);
                            $data['descricao'] = "Parcela de orçamento odontologico";
                            $data['pessoa_id'] = $paciente->id;
                            $data['instituicao_id'] = $instituicao;
                            $data['num_parcela'] = $key;
                            $data['valor_total'] = $data['valor_parcela'];
                            $data['usuario_baixou_id'] = (!empty($data['status'])) ? $usuario_logado->id : null;
                            
                            $contaReceber = $orcamento->contaReceber()->create($data);
                            $contaReceber->criarLogCadastro($usuario_logado, $instituicao);
                        }
                        
                    }
                }
            }
        });

        $odontologicos = $paciente->odontologicos()->orderBy('created_at', 'DESC')->with('prestador')->get();

        return view('instituicao.prontuarios.odontologicos.orcamentos', \compact('odontologicos'));
    }

    public function odontologicoConcluirProcedimento(Request $request, Pessoa $paciente, OdontologicoPaciente $orcamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'concluir_procedimento_odontologico');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($orcamento->paciente_id === $paciente->id, 403);

        $orcamento->loadMissing('itens', 'itens.procedimentos',  'itens.procedimentos.procedimentoInstituicao', 'itens.procedimentos.procedimentoInstituicao.procedimento', 'itens.regiao', 'itens.regiaoProcedimento');
        $prestadores = $instituicao->medicos()->orderBy('nome', 'asc')->get();

        return view('instituicao.prontuarios.odontologicos.modal_concluir_procedimentos', \compact('orcamento', 'prestadores'));
    }

    public function salvarOrcamentoProcedimentosAprovados(ConcluirProcedimentoOdontologicoRequest $request, Pessoa $paciente, OdontologicoPaciente $orcamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_orcamento_odontologico');
        $instituicao = $request->session()->get('instituicao');
        abort_unless($instituicao === $paciente->instituicao_id, 403);
        abort_unless($orcamento->paciente_id === $paciente->id, 403);

        $dados = $request->validated();
        $prestador_id = $dados['prestador_id'];
        unset($dados['prestador_id']);
        
        DB::transaction(function () use($dados, $request, $instituicao, $paciente, $orcamento, $prestador_id){
            $usuario_logado = $request->user('instituicao');
             $data = [
                'concluido' => 1, 
                'data_conclusao' => date('Y-m-d'),
                'prestador_id' => $prestador_id,
             ];

            foreach ($dados['orcamento'] as $key => $value) {
                
                $item = $orcamento->itens()->where('id', $value)->first();
                $procedimentoRepasse = ConveniosProcedimentos::find($item->procedimento_instituicao_convenio_id);
                $repasse = $procedimentoRepasse->repasseMedicoId($prestador_id)->first();

                if(!empty($repasse)){
                    $data['tipo'] = $repasse->pivot->tipo;
                    $data['valor_repasse'] = $repasse->pivot->valor_repasse;
                }

                $item->update($data);
                $item->criarLogEdicao($usuario_logado, $instituicao);
            }

            $itens = $orcamento->itens()->where('concluido', '0')->get();
            
            if(empty($itens) || count($itens) == 0){
                $orcamento->update(['finalizado' => 1, 'data_finalizado' => date('Y-m-d')]);
                $orcamento->criarLogEdicao($usuario_logado, $instituicao);
            }
        });

        $odontologicos = $paciente->odontologicos()->orderBy('created_at', 'DESC')->with('prestador')->get();

        return view('instituicao.prontuarios.odontologicos.orcamentos', \compact('odontologicos'));
    }

    public function cancelarItemConcluidoOrcamento(Request $request, Pessoa $paciente, OdontologicoPaciente $orcamento, OdontologicoItemPaciente $item)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cancelar_procedimento_concluido_odontologico');
        $instituicao = $request->session()->get('instituicao');

        abort_unless($instituicao === $paciente->instituicao_id, 403);
        abort_unless($orcamento->paciente_id === $paciente->id, 403);

        DB::transaction(function () use($request, $instituicao, $item, $orcamento){
            $usuario_logado = $request->user('instituicao');
             $data = [
                'concluido' => 0, 
                'data_conclusao' => null,
                'prestador_id' => null,
             ];

            $data['tipo'] = null;
            $data['valor_repasse'] = null;

            $item->update($data);
            $item->criarLogEdicao($usuario_logado, $instituicao);
            

        });

        $odontologicos = $paciente->odontologicos()->orderBy('created_at', 'DESC')->with('prestador')->get();

        return view('instituicao.prontuarios.odontologicos.orcamentos', \compact('odontologicos'));
    }

    public function getTableOrcamento(Request $request, Pessoa $paciente)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        $odontologicos = [];

        if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_orcamento_odontologico')) {
            return view('instituicao.prontuarios.odontologicos.orcamentos', \compact('odontologicos'));
        }

        $odontologicos = $paciente->odontologicos()->orderBy('created_at', 'DESC')->with('prestador')->get();

        return view('instituicao.prontuarios.odontologicos.orcamentos', \compact('odontologicos'));
    }

    public function cancelarOrcamentoOdontologico(Request $request, Pessoa $paciente, OdontologicoPaciente $orcamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cancelar_aprovado_orcamento_odontologico');
        $instituicao = $request->session()->get('instituicao');

        abort_unless($instituicao === $paciente->instituicao_id, 403);
        abort_unless($orcamento->paciente_id === $paciente->id, 403);

        DB::transaction(function() use($orcamento, $request, $instituicao){
            $usuario_logado = $request->user('instituicao');

            $dados = [
                'finalizado' => 0,
                'data_finalizado' => null,
                'data_aprovado' => null,
                'valor_aprovado' => null,
                'desconto' => null,
                'status' => 'criado',
            ];
            $orcamento->update($dados);
            $orcamento->criarLog($usuario_logado, 'Cancelamento de orçamento odontologico', $dados, $instituicao);

            $contaReceber = $orcamento->contaReceber()->get();
            if(count($contaReceber) > 0){
                foreach ($contaReceber as $key => $value) {
                    $value->delete();
                    $value->criarLog($usuario_logado, 'Exclusao de orcamento odontologico conta receber', null, $instituicao);
                }
            }

            $itens = $orcamento->itens()->where('concluido', 1)->get();
            if(count($itens) > 0){
                foreach ($itens as $key => $value) {
                    $data = [
                        'concluido' => 0, 
                        'data_conclusao' => null,
                        'prestador_id' => null,
                     ];
        
                    $data['tipo'] = null;
                    $data['valor_repasse'] = null;

                    $value->update($data);
                    $value->criarLog($usuario_logado, 'Cancelamento de orçamento odontologico procedimento', $data, $instituicao);
                }
            }
        });

        $odontologicos = $paciente->odontologicos()->orderBy('created_at', 'DESC')->with('prestador')->get();

        return view('instituicao.prontuarios.odontologicos.orcamentos', \compact('odontologicos'));
    }

    public function editarOrcamentoOdontologico(Request $request, Pessoa $paciente, OdontologicoPaciente $orcamento)
    {
        $instituicao = $request->session()->get('instituicao');

        abort_unless($instituicao === $paciente->instituicao_id, 403);
        abort_unless($orcamento->paciente_id === $paciente->id, 403);

        $itens = $orcamento->itens()->with('procedimentos',  'procedimentos.procedimentoInstituicao', 'procedimentos.procedimentoInstituicao.procedimento', 'regiao', 'procedimentosItens', 'regiaoProcedimento')->get();
        return response()->json($itens);
    }

    public function odontologicoEditar(CriarOdontologicoProcedimentosRequest $request, $agendamento = null, Pessoa $paciente, OdontologicoPaciente $orcamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_orcamento_odontologico');
        $instituicao_id = $request->session()->get('instituicao');
        abort_unless($instituicao_id === $paciente->instituicao_id, 403);
        abort_unless($orcamento->paciente_id === $paciente->id, 403);
        
        if($agendamento != 'null'){
            $agendamento = Agendamentos::find($agendamento);
            abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        }

        $dados = $request->validated();

        DB::transaction(function() use($dados, $paciente, $orcamento, $instituicao_id, $request){
            $usuario_logado = $request->user('instituicao');
            $valor_total = 0;
            $dadosItensGeral = [];
            foreach ($dados['itens'] as $key => $value) {
                // $procedimentoConvenio = ConveniosProcedimentos::find($value['procedimento']);
                // $valor_total += $procedimentoConvenio->valor;
                
                $dadosItens = [
                    'status' => 'aprovado',
                    // 'valor' => $procedimentoConvenio->valor,
                    // 'valor_convenio' => $procedimentoConvenio->valor_convenio,
                    'dente_id' => $value['dente'],
                    'procedimento_id' => $value['procedimento'],
                    // 'regiao_procedimento_id' => (array_key_exists('regiao', $value)) ? $value['regiao'] : null,
                ];

                if($value['tipo'] == 'novo'){
                    $item = $orcamento->itens()->create($dadosItens);
                    $dadosItensGeral[] = $dadosItens;

                    if(array_key_exists('regiao', $value)){
                        $dadosRegiao = explode(',',$value['regiao']);
                        $item->regiaoProcedimento()->attach($dadosRegiao);
                    }

                }else if($value['tipo'] == 'excluido'){
                    $item = OdontologicoItemPaciente::find($value['id']);
                    if($item->odontologico_paciente_id == $orcamento->id){
                        $item->delete();
                        $item->criarLog($usuario_logado, "Exclusão item do orcamento {$orcamento->id}", null, $instituicao_id);
                    }
                }

            }

            $orcamento->criarLog($usuario_logado, 'Itens do orçamento odontologico', $dadosItensGeral, $instituicao_id);
            // $orcamento->update(['valor_total' => $valor_total]);
        });
        
        $odontologicos = $paciente->odontologicos()->orderBy('created_at', 'DESC')->with('prestador')->get();

        return view('instituicao.prontuarios.odontologicos.orcamentos', \compact('odontologicos'));
    }

    public function excluirOrcamentoOdontologico(Request $request, Pessoa $paciente, OdontologicoPaciente $orcamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_orcamento_odontologico');
        $instituicao = $request->session()->get('instituicao');
        abort_unless($instituicao === $paciente->instituicao_id, 403);
        abort_unless($orcamento->paciente_id === $paciente->id, 403);

        DB::transaction(function() use($orcamento, $request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $orcamento->delete();
            $orcamento->criarLogExclusao($usuario_logado, $instituicao);
        });

        $odontologicos = $paciente->odontologicos()->orderBy('created_at', 'DESC')->with('prestador')->get();

        return view('instituicao.prontuarios.odontologicos.orcamentos', \compact('odontologicos'));
    }
    
    public function imprimirOrcamento(Request $request, Pessoa $paciente, OdontologicoPaciente $orcamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($orcamento->paciente_id === $paciente->id, 403);
        
        $orcamento->loadMissing('itens', 'itens.procedimentos',  'itens.procedimentos.procedimentoInstituicao', 'itens.procedimentos.procedimentoInstituicao.procedimento', 'itens.regiao', 'itens.procedimentosItens', 'itens.regiaoProcedimento');

        $valor_orcamento = 0;
        $itensValores = [];
        if($orcamento->status == 'criado' || $orcamento->status == 'reprovado'){
            $itens = explode(';',$request->input('ids_convenio'));
            $itensIds = [];
            foreach ($itens as $key => $value) {
                $dadosIten = explode(',',$value);
                $itensIds[] = $dadosIten[0];
                $item = $orcamento->itens()->where('id', $dadosIten[0])->first();
                if(!empty($item)){
                    $procedimento = $item->procedimentosItens()->with(['procedimentoInstituicaoOdontologico.conveniosProcedimentos' => function($q) use($dadosIten){ 
                        $q->where('convenios_id', $dadosIten[1]);
                    }])->first();
    
                    $valor_orcamento += $procedimento->procedimentoInstituicaoOdontologico[0]->conveniosProcedimentos[0]->valor + $dadosIten[2];
    
                    $itensValores[$dadosIten[0]] = [
                        'procedimento_instituicao_convenio_id' => $procedimento->procedimentoInstituicaoOdontologico[0]->conveniosProcedimentos[0]->id,
                        'valor' => $procedimento->procedimentoInstituicaoOdontologico[0]->conveniosProcedimentos[0]->valor,
                        'valor_convenio' => $procedimento->procedimentoInstituicaoOdontologico[0]->conveniosProcedimentos[0]->valor_convenio,
                        'valor_custo' => $procedimento->valor_custo,
                        'desconto' => $dadosIten[2]
                    ];
                }
            }
        }

        $prestador = $instituicao->prestadores()->whereHas('modeloImpressao')->with('modeloImpressao')->first();
        
        $impressao = null;
        if(!empty($prestador)){
            $impressao = $prestador->modeloImpressao()->first();
        }

        // return view('instituicao.prontuarios.odontologicos.imprimir_tudo', \compact('orcamento', 'impressao'));
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML(view('instituicao.prontuarios.odontologicos.imprimir_tudo', \compact('orcamento', 'impressao', 'valor_orcamento', 'itensValores')));
        return $pdf->stream();
    }
    
    public function imprimirOrcamentoTotal(Request $request, Pessoa $paciente, OdontologicoPaciente $orcamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($orcamento->paciente_id === $paciente->id, 403);
        // dd($request->input('ids_convenio'));
        $orcamento->loadMissing('itens', 'itens.procedimentos',  'itens.procedimentos.procedimentoInstituicao', 'itens.procedimentos.procedimentoInstituicao.procedimento', 'itens.regiao', 'itens.procedimentosItens', 'itens.regiaoProcedimento');

        $valor_orcamento = 0;
        $itensValores = [];
        if($orcamento->status == 'criado' || $orcamento->status == 'reprovado'){
            $itens = explode(';',$request->input('ids_convenio'));
            $itensIds = [];
            foreach ($itens as $key => $value) {
                $dadosIten = explode(',',$value);
                $itensIds[] = $dadosIten[0];
                $item = $orcamento->itens()->where('id', $dadosIten[0])->first();
                if(!empty($item)){
                    $procedimento = $item->procedimentosItens()->with(['procedimentoInstituicaoOdontologico.conveniosProcedimentos' => function($q) use($dadosIten){ 
                        $q->where('convenios_id', $dadosIten[1]);
                    }])->first();
    
                    $valor_orcamento += $procedimento->procedimentoInstituicaoOdontologico[0]->conveniosProcedimentos[0]->valor + $dadosIten[2];
    
                    $itensValores[$dadosIten[0]] = [
                        'procedimento_instituicao_convenio_id' => $procedimento->procedimentoInstituicaoOdontologico[0]->conveniosProcedimentos[0]->id,
                        'valor' => $procedimento->procedimentoInstituicaoOdontologico[0]->conveniosProcedimentos[0]->valor,
                        'valor_convenio' => $procedimento->procedimentoInstituicaoOdontologico[0]->conveniosProcedimentos[0]->valor_convenio,
                        'valor_custo' => $procedimento->valor_custo,
                        'desconto' => $dadosIten[2]
                    ];
                }
            }
        }

        $prestador = $instituicao->prestadores()->whereHas('modeloImpressao')->with('modeloImpressao')->first();
        
        $impressao = null;
        if(!empty($prestador)){
            $impressao = $prestador->modeloImpressao()->first();
        }

        // return view('instituicao.prontuarios.odontologicos.imprimir_tudo', \compact('orcamento', 'impressao'));
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML(view('instituicao.prontuarios.odontologicos.imprimir_total', \compact('orcamento', 'impressao', 'valor_orcamento', 'itensValores')));
        return $pdf->stream();
    }

    public function contratoOrcamento(Request $request, Pessoa $paciente, OdontologicoPaciente $orcamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($orcamento->paciente_id === $paciente->id, 403);

        $orcamento->loadMissing('itens', 'itens.procedimentos',  'itens.procedimentos.procedimentoInstituicao', 'itens.procedimentos.procedimentoInstituicao.procedimento', 'itens.regiao');

        $contas = $orcamento->contaReceber()->select('forma_pagamento', DB::raw("SUM(valor_parcela) as `valor_total`"), DB::raw("COUNT(id) AS `qtd_parcelas`"))->groupBy('forma_pagamento')->get();

        $idade = null;
        if($paciente->nascimento){
            $idade = ConverteValor::calcularIdade($paciente->nascimento);
        }

        return view('instituicao.prontuarios.odontologicos.contrato_odontologico', \compact('orcamento', 'contas', 'instituicao', 'paciente', 'idade'));
    }

    public function geraBoelto(Request $request, OdontologicoPaciente $orcamento){
        $contas_rec = ContaReceber::
            where('odontologico_id', $orcamento->id)
            ->where('forma_pagamento', 'boleto_cobranca')
        ->get();

        $contasReceber = new ContasReceber;
        $boletos = "";
        $i = "";
        
        foreach($contas_rec as $item){
            $i = $contasReceber->geraBoleto($request, $item);
            if(is_array($i)){
                break;
            }else{
                $boletos .= $i;
            }
        }

        if(is_array($i)){
            return $i;
        }else{
            return $boletos;
        }        
        
        foreach($contas_rec as $item){
            $boletos .= $contasReceber->geraBoleto($request, $item);
        }

        return $boletos;
    } 

    public function alterarValorLaboratorio(AlterarLaboratorioOdontologicoRequest $request, Pessoa $paciente ,OdontologicoPaciente $orcamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_laboratorio_odontologico');
        $instituicao = $request->session()->get('instituicao');
        abort_unless($instituicao === $paciente->instituicao_id, 403);
        abort_unless($orcamento->paciente_id === $paciente->id, 403);

        $dados = $request->validated();
        
        DB::transaction(function () use($dados, $request, $instituicao, $orcamento){
            $usuario_logado = $request->user('instituicao');

            $itens = explode(';',$dados['ids']);
            $itensIds = [];

            foreach ($itens as $key => $value) {
                $dadosIten = explode(',',$value);
                $itensIds[] = $dadosIten[0];
                $item = $orcamento->itens()->where('id', $dadosIten[0])->first();
                if(!empty($item)){
                    $laboratorio = (!empty($dadosIten[1])) ? $dadosIten[1] : 0;
                    
                    $dataIten = [
                        'laboratorio' => $laboratorio,
                    ];
                    $item->update($dataIten);
                    $item->criarLogEdicao($usuario_logado, $instituicao);
                }
            }
        });

        return response()->json(true);
    }
}
