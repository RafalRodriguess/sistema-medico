<?php

namespace App\Http\Controllers\API;

use App\Agendamentos;
use App\Convenio;
use App\GruposProcedimentos;
use App\ConveniosProcedimentos;
use App\Http\Controllers\Controller;
use App\Http\Resources\AgendaCollection;
use App\Http\Resources\AgendaResource;
use App\Http\Resources\ConvenioProcedimentoCollection;
use App\Http\Resources\FinalizarExameResource;
use App\Http\Resources\GruposProcedimentosCollection;
use App\Http\Resources\ProcedimentosCollection;
use App\Instituicao;
use App\Libraries\PagarMe;
use App\UsuarioCartao;
use App\UsuarioEndereco;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AgendaGruposProcedimentosController extends Controller
{
    public function grupos(Request $request)
    {
        // dd($request->all());
        $grupo = GruposProcedimentos::

        whereHas('procedimentos_instituicoes',function($q) use ($request){
            $q->where('instituicoes_id', $request->instituicao)
            ->when($request->bateriaExame==true,function($q) use ($request){
                $q->where(function($q){
                   $q->where('tipo','avulso')->orWhere('tipo','ambos');
                });
            })
            ->when($request->bateriaExame==false,function($q) use ($request){
                $q->where(function($q){
                   $q->where('tipo','unico')->orWhere('tipo','ambos');
                });
            })
            ->has('instituicaoProcedimentosConvenios');
        })->get();

        return new GruposProcedimentosCollection($grupo);
    }

    public function grupoProcedimentos(Request $request)
    {

        $grupoProcedimentos = GruposProcedimentos::
        where('id', $request->filtros['grupo'])
        ->with(['procedimentos_instituicoes' => function($qProcedimentoInstituicao) use( $request){
            $qProcedimentoInstituicao->where('instituicoes_id', $request->filtros['instituicaoId']);
            $qProcedimentoInstituicao->when($request->filtros['bateriaExame']==false,function($q){
                $q->where(function($q){
                   $q->where('tipo','unico')->orWhere('tipo','ambos');
                });
            });
            $qProcedimentoInstituicao->when($request->filtros['bateriaExame']==true,function($q){
                $q->where(function($q){
                   $q->where('tipo','avulso')->orWhere('tipo','ambos');
                });
            });
            $qProcedimentoInstituicao->with(['procedimento' => function($query) {
                $query->where('tipo', 'exame');
            },
            'instituicaoProcedimentosConvenios']);
        }])->first();
        //  dd($grupoProcedimentos->procedimentos_instituicoes);
        $procedimentos = [];

        if($grupoProcedimentos){
            foreach ($grupoProcedimentos->procedimentos_instituicoes as $key => $value) {
                if($value->procedimento && sizeof($value->instituicaoProcedimentosConvenios) > 0){
                    $auxiliar = $value->procedimento;
                    $auxiliar['convenios'] = $value->instituicaoProcedimentosConvenios;
                    array_push($procedimentos, $auxiliar);
                }
            }
        }
        // dd($procedimentos);
        if(!$procedimentos)
        {
            return [
                'data' => null
            ];
        }

        return new ProcedimentosCollection($procedimentos);
    }


    public function agendaProcedimento(Request $request)
    {
        $dia_semana = date('w', strtotime($request->filtros['data']));
        $dia_semana = $this->convertDiaSemana($dia_semana);
        $dia_mes = date('d/m/Y', strtotime($request->filtros['data']));
        $dia_mes_unico = date('Y-m-d', strtotime($request->filtros['data']));

        $grupoAgenda = GruposProcedimentos::where('id', $request->filtros['grupo'])
        ->when($request->filtros['bateriaExame']==false,function($q) use($dia_mes, $dia_semana, $request, $dia_mes_unico){
            $q->with(['procedimentos_instituicoes' => function($qProcedimentoInstituicao) use($dia_mes, $dia_semana, $request, $dia_mes_unico){
                $qProcedimentoInstituicao->where('instituicoes_id', $request->filtros['instituicaoId']);
                $qProcedimentoInstituicao->where('procedimentos_id', $request->filtros['procedimento']);
                $qProcedimentoInstituicao->with(['agenda' => function($query) use($dia_mes, $dia_semana, $dia_mes_unico) {
                    $query->where('referente', 'procedimento');
                    $query->where(function($q) use($dia_mes, $dia_semana){
                        $q->orWhere('dias_continuos', $dia_semana);
                        $q->orWhere(function($qUnicos) use($dia_mes){
                            $qUnicos->whereJsonContains("dias_unicos", ['date' => $dia_mes]);
                        });
                    });
                    $query->with(['agendamentos' => function ($queryAgenda) use($dia_mes_unico) {
                        $queryAgenda->whereDate('data', $dia_mes_unico);
                        $queryAgenda->where('status', '!=', 'cancelado');
                    }]);
                }]);
            }]);
        })
        ->when($request->filtros['bateriaExame']==true,function($q) use($dia_mes, $dia_semana, $request, $dia_mes_unico){
            $q->with(['instituicoes' => function($qInstituicao) use($dia_mes, $dia_semana, $request, $dia_mes_unico){
                $qInstituicao->where('instituicao_id', $request->filtros['instituicaoId']);
                $qInstituicao->with(['agenda' => function($query) use($dia_mes, $dia_semana, $dia_mes_unico) {
                    $query->where('referente', 'grupo');
                    $query->where(function($q) use($dia_mes, $dia_semana){
                        $q->orWhere('dias_continuos', $dia_semana);
                        $q->orWhere(function($qUnicos) use($dia_mes){
                            $qUnicos->whereJsonContains("dias_unicos", ['date' => $dia_mes]);
                        });
                    });
                    $query->with(['agendamentos' => function ($queryAgenda) use($dia_mes_unico) {
                        $queryAgenda->whereDate('data', $dia_mes_unico);
                        $queryAgenda->where('status', '!=', 'cancelado');
                    }]);
                }]);
            }]);
        })
        ->first();
        // dd($grupoAgenda->instituicoes[0]->agenda   );
        $agendaProcedimento = null;
        if($request->filtros['bateriaExame']==true){

            foreach ($grupoAgenda->instituicoes as $key => $value) {
                if($value->agenda){
                    $agendaProcedimento = null;
                    foreach ($value->agenda as $keyAgenda => $agenda) {
                        if ($agenda->dias_unicos) {
                            $diasUnicos = $agenda->dias_unicos;
                            $diasUnicos = json_decode($diasUnicos);
                            foreach ($diasUnicos as $keyUnico => $dUnico) {
                                if ($dUnico->date == $dia_mes) {
                                    $agendaUnico = [
                                        'id' => $agenda->id,
                                        'hora_inicio' => $dUnico->hora_inicio,
                                        'hora_fim' => $dUnico->hora_fim,
                                        'hora_intervalo' => $dUnico->hora_intervalo,
                                        'duracao_intervalo' => $dUnico->duracao_intervalo,
                                        'duracao_atendimento' => $dUnico->duracao_atendimento,
                                    ];
                                    $agendaProcedimento = $agendaUnico;

                                    if ($agenda->agendamentos) {
                                        $agendaProcedimento['agendamentos'] = $agenda->agendamentos;
                                    }else{
                                        $agendaProcedimento['agendamentos'] = null;
                                    }
                                }
                            }
                        } else {
                            if (!$agendaProcedimento) {
                                $agendaUnico = [
                                    'id' => $agenda->id,
                                    'hora_inicio' => $agenda->hora_inicio,
                                    'hora_fim' => $agenda->hora_fim,
                                    'hora_intervalo' => $agenda->hora_intervalo,
                                    'duracao_intervalo' => $agenda->duracao_intervalo,
                                    'duracao_atendimento' => $agenda->duracao_atendimento,
                                ];
                                $agendaProcedimento = $agendaUnico;

                                if ($agenda->agendamentos) {
                                    // dd($agenda->agendamentos);
                                    $agendaProcedimento['agendamentos'] = $agenda->agendamentos;
                                }else{
                                    $agendaProcedimento['agendamentos'] = null;
                                }
                            }
                        }
                    }
                }
            }
        }else if($request->filtros['bateriaExame']==false){
            foreach ($grupoAgenda->procedimentos_instituicoes as $key => $value) {
                if($value->agenda){
                    $agendaProcedimento = null;
                    foreach ($value->agenda as $keyAgenda => $agenda) {
                        if ($agenda->dias_unicos) {
                            $diasUnicos = $agenda->dias_unicos;
                            $diasUnicos = json_decode($diasUnicos);
                            foreach ($diasUnicos as $keyUnico => $dUnico) {
                                if ($dUnico->date == $dia_mes) {
                                    $agendaUnico = [
                                        'id' => $agenda->id,
                                        'hora_inicio' => $dUnico->hora_inicio,
                                        'hora_fim' => $dUnico->hora_fim,
                                        'hora_intervalo' => $dUnico->hora_intervalo,
                                        'duracao_intervalo' => $dUnico->duracao_intervalo,
                                        'duracao_atendimento' => $dUnico->duracao_atendimento,
                                    ];
                                    $agendaProcedimento = $agendaUnico;

                                    if ($agenda->agendamentos) {
                                        $agendaProcedimento['agendamentos'] = $agenda->agendamentos;
                                    }else{
                                        $agendaProcedimento['agendamentos'] = null;
                                    }
                                }
                            }
                        } else {
                            if (!$agendaProcedimento) {
                                $agendaUnico = [
                                    'id' => $agenda->id,
                                    'hora_inicio' => $agenda->hora_inicio,
                                    'hora_fim' => $agenda->hora_fim,
                                    'hora_intervalo' => $agenda->hora_intervalo,
                                    'duracao_intervalo' => $agenda->duracao_intervalo,
                                    'duracao_atendimento' => $agenda->duracao_atendimento,
                                ];
                                $agendaProcedimento = $agendaUnico;

                                if ($agenda->agendamentos) {
                                    $agendaProcedimento['agendamentos'] = $agenda->agendamentos;
                                }else{
                                    $agendaProcedimento['agendamentos'] = null;
                                }
                            }
                        }
                    }
                }
            }
        }

        if(!$agendaProcedimento)
        {
            return [
                'data' => null
            ];
        }

        return new AgendaResource($agendaProcedimento);
    }

    private function convertDiaSemana($dia_semana)
    {
        $dia = [
            '0' => 'domingo',
            '1' => 'segunda',
            '2' => 'terca',
            '3' => 'quarta',
            '4' => 'quinta',
            '5' => 'sexta',
            '6' => 'sabado',
        ];
        return $dia[$dia_semana];
    }

    public function getDadosExameFinalizar(Request $request)
    {

        $dados = Instituicao::where('id',$request->filtros['instituicaoId'])->with(['procedimentos' => function($qProcedimento) use($request){
            $qProcedimento->where('procedimentos_id', $request->filtros['procedimentoId']);
            $qProcedimento->with(['procedimentoInstituicao' => function($qProcedimentoInstituicao) use($request){
                $qProcedimentoInstituicao->where('grupo_id', $request->filtros['grupoId']);
                $qProcedimentoInstituicao->with(['instituicaoProcedimentosConvenios' => function($qConvenio) use($request){
                    $qConvenio->where('convenios_id', $request->filtros['convenioId']);
                }, 'grupoProcedimento']);
            }]);
        }])->first();
        return new FinalizarExameResource($dados);
    }

    public function finalizarBateriaExame(Request $request)
    {
        $dadosFinalizarExame = $request->form;
        $validacao = [];
        $cartao_pagarme = [];
        $agendas = [];
        ///////////BUSCA INSTITUICAÇÃO
        $instituicao = Instituicao::find($dadosFinalizarExame['instituicaoId']);

        if(!$instituicao){
            return $validacao[] = [
                'erro' => 'Instituição',
                'texto' => 'Instituição não existe'
            ];
        }

        $totalExame = 0;

        foreach($dadosFinalizarExame['procedimentos'] as $requestprocedimento){
            // dd($requestprocedimento['data']);
            ///////////CRIAR HORARIO AGENDAMENTO ESCOLHIDO
            $horarioAgenda = \Carbon\Carbon::parse($requestprocedimento['data'])->setTimeFromTimeString($requestprocedimento['horario']);
            $dia_mes = $horarioAgenda->format('d/m/Y');
            $data = $horarioAgenda->format('Y-m-d');
            // $horarioAgenda = $dadosFinalizarExame['data'].' '.$dadosFinalizarExame['horario_agendado'].':00';
            $objAgenda = ['agenda'=>$requestprocedimento['agendaId'], 'data'=> $horarioAgenda];
            $objInserted = false;
            for($i = 0;$i < count($agendas); $i++){
                if($agendas[$i]['agenda'] == $objAgenda['agenda']) {
                    $objInserted = true;
                }
            }
            if($objInserted == false){
                    array_push($agendas, $objAgenda);
            }
            // $horarioAgenda = date('Y-m-d H:i:s', strtotime($horarioAgenda));
            // $dia_mes = date('d/m/Y', strtotime($dadosFinalizarExame['data']));
            //////////VALIDAÇÃO DE PROCEDIMENTOS PERTENCE A INSTITUIÇÃO E PEGA AGENDA DO PROCEDIMENTO

            $procedimento = $instituicao->procedimentosInstituicoes()
                ->where('procedimentos_id', $requestprocedimento['id'])
                ->where('grupo_id', $requestprocedimento['grupoId'])
                ->with(['grupoProcedimento.instituicoes'])
                ->with(['grupoProcedimento.instituicoes' => function($q) use ($dadosFinalizarExame){
                    $q->where('instituicao_id', $dadosFinalizarExame['instituicaoId']);
                },
                'grupoProcedimento.instituicoes.agenda' => function($query) use($requestprocedimento, $horarioAgenda){
                    $query->where('referente', 'grupo');
                    $query->where('id', $requestprocedimento['agendaId']);
                    $query->with(['agendamentos' => function($qAgendamentos) use($horarioAgenda, $requestprocedimento){
                        $qAgendamentos->where('instituicoes_agenda_id', $requestprocedimento['agendaId']);
                        $qAgendamentos->where('data', $horarioAgenda);
                        $qAgendamentos->where('status', '!=', 'cancelado');
                    }]);
                }])
            ->first();

            if (!$procedimento) {
                return $validacao[] = [
                    'erro' => 'Procedimento',
                    'texto' => 'Procedimento não atende por esta instituição'
                ];
            }
            ///////////VERIFICAR SE HORARIO ESTA DISPONIVEL
            if(sizeof($procedimento->grupoProcedimento->instituicoes[0]->agenda) == 0){
                return $validacao[] = [
                    'erro' => 'Horário',
                    'texto' => 'Horário selecionado indisponível'
                ];
            }


            if(sizeof($procedimento->grupoProcedimento->instituicoes[0]->agenda) > 0){
                if(sizeof($procedimento->grupoProcedimento->instituicoes[0]->agenda[0]->agendamentos) > 0){
                    return $validacao[] = [
                        'erro' => 'Horário',
                        'texto' => 'Horário selecionado indisponível'
                    ];
                }
            }

            ///////////VERIFICA SE CONTEM DIA UNICO
            $agendaProcedimento = $this->getAgendaDia($procedimento->grupoProcedimento->instituicoes[0]->agenda, $dia_mes);

            if (!$agendaProcedimento) {
                return $validacao[] = [
                    'erro' => 'Agenda',
                    'texto' => 'Titular não atende nessa data'
                ];
            }

            ///////////VERIFICA SE PRESTADOR ATENDE NESSE HORARIO
            $dataAtendimento['hora_inicio'] = $this->retornaData($agendaProcedimento['hora_inicio'], $data);
            $dataAtendimento['hora_fim'] = $this->retornaData($agendaProcedimento['hora_fim'], $data);
            $dataAtendimento['hora_atendimento'] = $this->retornaData($agendaProcedimento['hora_inicio'], $data);
            $dataAtendimento['duracao_intervalo'] = $agendaProcedimento['duracao_intervalo'];
            $dataAtendimento['hora_intervalo'] = $this->retornaData($agendaProcedimento['hora_intervalo'], $data);
            $dataAtendimento['hora_escolhida_usuario'] = $this->retornaData($horarioAgenda);
            $dataAtendimento['tempo_atendimento'] = $agendaProcedimento['duracao_atendimento'];

            $dataAtendimento['totalAtendimentos'] = $this->getTotalAtendimento($dataAtendimento['hora_inicio'], $dataAtendimento['hora_fim'], $agendaProcedimento['duracao_atendimento']);
            // dd($dataAtendimento);
            $existeHorarioAtendimento = $this->existeHorarioAtendimento($dataAtendimento);

            if ($existeHorarioAtendimento == false) {
                return $validacao[] = [
                    'erro' => 'Horário',
                    'texto' => 'Procedimento não atende nesse horário'
                ];
            }

            ///////////VERIFICA SE REALIZA PROCEDIMENTO PELO CONVENIO
            $convenio = $procedimento->instituicaoProcedimentosConvenios()->where('convenios_id', $requestprocedimento['convenioSelecionado']['convenio_id'])->first();

            if (!$convenio) {
                return $validacao[] = [
                    'erro' => 'Convênio',
                    'texto' => 'Procedimento não atende pelo convênio selecionado'
                ];
            }

            $totalExame += $convenio->pivot->valor;

        }

        if($dadosFinalizarExame['tipoPagamentoForm'] == 'cartao_entrega'){
            if(!$instituicao->cartao_entrega){
                return $validacao [] = [
                    'erro' => 'Tipo pagamento',
                    'texto' => 'Instituição não aceita forma de pagamento'
                ];
            }
        }
        
        if($dadosFinalizarExame['tipoPagamentoForm'] == 'dinheiro'){
            if(!$instituicao->dinheiro){
                return $validacao [] = [
                    'erro' => 'Tipo pagamento',
                    'texto' => 'Instituição não aceita forma de pagamento'
                ];
            }
        }

        if($dadosFinalizarExame['tipoPagamentoForm'] == 'cartao_credito'){
            ///VALIDAÇÃO PARCELAS
            if($dadosFinalizarExame['parcelas'] <= $instituicao->max_parcela && $dadosFinalizarExame['parcelas'] > 0 ){
                if($dadosFinalizarExame['parcelas'] > $instituicao->free_parcela){
    
                    $parcelasTaxa = $dadosFinalizarExame['parcelas'] - $instituicao->free_parcela;
                    $valorTaxa = $parcelasTaxa * $instituicao->valor_parcela;
    
                    $valorNovo = ($totalExame * ( 1 + ($valorTaxa / 100 ) ) );
                    $totalExame = number_format($valorNovo, 2);
                    $valorParcelas = $totalExame / $dadosFinalizarExame['parcelas'];
                    $valorParcelas = number_format($valorParcelas, 2);
    
                    if($valorParcelas < $instituicao->valor_minimo && $dadosFinalizarExame['parcelas'] != 1){
                        return $validacao[] = [
                            'erro' => 'Parcelas',
                            'texto' => 'Numero de parcelas inválido!',
                        ];
                    }
    
                }else{
                    $valorParcelas = $totalExame / $dadosFinalizarExame['parcelas'];
    
                    if($valorParcelas < $instituicao->valor_minimo && $dadosFinalizarExame['parcelas'] != 1){
                        return $validacao[] = [
                            'erro' => 'Parcelas',
                            'texto' => 'Numero de parcelas inválido!',
                        ];
                    }
                }
    
            }else{
                return $validacao[] = [
                    'erro' => 'Parcelas',
                    "texto" => "Numero de parcelas inválido!"
                ];
            }    
    
            //////////VALIDAÇÃO CARTÃO USUARIO
            $enderecoCobranca = UsuarioEndereco::where('usuario_id',$request->user('sanctum')->id)->where('id', $dadosFinalizarExame['endereco'])->first();
    
            if(!empty($enderecoCobranca)){
                $endereco_cartao = [
                    'rua' => $enderecoCobranca->rua,
                    'numero' => $enderecoCobranca->numero,
                    'bairro' => $enderecoCobranca->bairro,
                    'cidade' => $enderecoCobranca->cidade,
                    'estado' => $enderecoCobranca->estado,
                    'complemento' => $enderecoCobranca->complemento,
                    'referencia' => $enderecoCobranca->referencia,
                    'cep' => $enderecoCobranca->cep,
                ];
                ///VALIDAÇÃO CARTÃO USUARIO
                if($dadosFinalizarExame['cartao']){
                    ///USAR CARTÃO EXISTENTE
                    $cartao = UsuarioCartao::where('usuario_id', $request->user('sanctum')->id)->where('id', $dadosFinalizarExame['cartao'])->first();
    
                    if(empty($cartao)){
                        return $validacao[] = [
                            'erro' => 'Cartão',
                            "texto" => "Cartão não existe!"
                        ];
                    }
    
    
                    $dados_cartao = [
                        'cvv' => $dadosFinalizarExame['cvv'],
                        'id_pagarme' => $cartao->id_pagarme,
                        'nome_cartao' => $cartao->nome,
                        'id' => $cartao->id
                    ];
                    $cartao_pagarme = array_merge($endereco_cartao, $dados_cartao);
                }else{
    
                    if($dadosFinalizarExame['numero_cartao'] && $dadosFinalizarExame['nome_cartao'] && $dadosFinalizarExame['data_validade']){
                        $dados_cartao = [
                            'numero_cartao' => $dadosFinalizarExame['numero_cartao'],
                            'nome_cartao' => $dadosFinalizarExame['nome_cartao'],
                            'data_validade' => $dadosFinalizarExame['data_validade'],
                            'cvv' => $dadosFinalizarExame['cvv']
                        ];
                        $cartao_pagarme = array_merge($endereco_cartao, $dados_cartao);
    
                    }else{
                        return $validacao[] = [
                            'erro' => 'Cartão',
                            "texto" => "Escolha um cartão existente!"
                        ];
                    }
    
                }
            }else{
                return $validacao[] = [
                    'erro' => 'Endereço',
                    "texto" => "Endereço de cobrança não existe!"
                ];
            }
        }

        $transacao = DB::transaction(function () use ($instituicao,$agendas, $request, $totalExame, $dadosFinalizarExame, $cartao_pagarme, $horarioAgenda, $requestprocedimento){

            $listaAgendamentos =[];
            foreach( $agendas as $agenda){

                $agendamentos = [
                    'tipo' => 'agendamento',
                    'data' => $agenda['data'],
                    'status' => 'pendente',
                    'valor_total' => $totalExame,
                    'parcelas' => $dadosFinalizarExame['parcelas'],
                    'porcento_parcela' => $instituicao->valor_parcela,
                    'free_parcelas' => $instituicao->free_parcela,
                    'instituicoes_agenda_id' => $agenda['agenda'],
                    'usuario_id' => $request->user('sanctum')->id,
                    'cartao_id' => $dadosFinalizarExame['cartao'],
                    'forma_pagamento' => $dadosFinalizarExame['tipoPagamentoForm'],
                ];

                $agendamento = Agendamentos::create($agendamentos);

                foreach($dadosFinalizarExame['procedimentos'] as $requestprocedimento){
                    if($requestprocedimento['agendaId'] == $agenda['agenda']){
                        $convenioProcedimento = ConveniosProcedimentos::
                        where('convenios_id',$requestprocedimento['convenioSelecionado']['convenio_id'])
                        ->whereHas('procedimentoInstituicao',function($q) use ($requestprocedimento, $dadosFinalizarExame){
                            $q->where('instituicoes_id', $dadosFinalizarExame['instituicaoId'])
                            ->where('procedimentos_id',$requestprocedimento['id'])
                            ->where('grupo_id',$requestprocedimento['grupoId']);
                        })
                        ->first();
                        // dd($convenioProcedimento);
                        $agendamentoProcedimento = [
                            'procedimentos_instituicoes_convenios_id' => $convenioProcedimento->id,
                            'valor_atual' => $requestprocedimento['convenioSelecionado']['valor'],
                        ];
                        $agendamento->agendamentoProcedimento()->create($agendamentoProcedimento);
                    }

                }

                array_push($listaAgendamentos,$agendamento );



            }


            if($dadosFinalizarExame['tipoPagamentoForm'] == 'cartao_credito'){
                $pagarMe = new PagarMe();
                return $pagarMe->criarTransacaoAgendaBateriaExame($listaAgendamentos, $instituicao, $cartao_pagarme);
            }

            return (object) [
                'status' => 'sucesso',
                'texto' => 'Agendamento realizado'
            ];

        });

        if(property_exists($transacao,'error')){
            return $validacao[] = [
                'erro' => 'Transação',
                'texto' => 'Ocorreu um erro na transação, tente novamente!'
            ];

        }

        if($transacao->status == 'refused'){
            $resultado = MotivoRecusaPagarme($transacao);
            return $validacao[] =
            [
                'texto' => $resultado['msg'],
                'status' => $transacao->status,
                'descricao' => $resultado['orientacao']
            ];
        }else{

            return $validacao[] =
            [
                'texto' => 'Agendamento realizado',
                'status' => $transacao->status
            ];
        }


    }

    public function finalizarExame(Request $request)
    {

        $dadosFinalizarExame = $request->form;
        $validacao = [];
        $cartao_pagarme = [];


        ///////////BUSCA INSTITUICAÇÃO
        $instituicao = Instituicao::find($dadosFinalizarExame['instituicaoId']);

        if(!$instituicao){
            return $validacao[] = [
                'erro' => 'Instituição',
                'texto' => 'Instituição não existe'
            ];
        }

        ///////////CRIAR HORARIO AGENDAMENTO ESCOLHIDO
        $horarioAgenda = $dadosFinalizarExame['data'].' '.$dadosFinalizarExame['horario_agendado'].':00';
        $horarioAgenda = date('Y-m-d H:i:s', strtotime($horarioAgenda));
        //////////VALIDAÇÃO DE PROCEDIMENTOS PERTENCE A INSTITUIÇÃO E PEGA AGENDA DO PROCEDIMENTO
        $dia_mes = date('d/m/Y', strtotime($dadosFinalizarExame['data']));

        $procedimento = $instituicao->procedimentosInstituicoes()
        ->where('procedimentos_id', $dadosFinalizarExame['procedimentoId'])
        ->where('grupo_id', $dadosFinalizarExame['grupoId'])
        ->with(['agenda' => function($query) use($dadosFinalizarExame, $horarioAgenda){
            $query->where('referente', 'procedimento');
            $query->where('id', $dadosFinalizarExame['agendaId']);
            $query->with(['agendamentos' => function($qAgendamentos) use($horarioAgenda, $dadosFinalizarExame){
                $qAgendamentos->where('instituicoes_agenda_id', $dadosFinalizarExame['agendaId']);
                $qAgendamentos->where('data', $horarioAgenda);
                $qAgendamentos->where('status', '!=', 'cancelado');
            }]);
        }])->first();

        if (!$procedimento) {
            return $validacao[] = [
                'erro' => 'Procedimento',
                'texto' => 'Procedimento não atende por esta instituição'
            ];
        }

        ///////////VERIFICAR SE HORARIO ESTA DISPONIVEL
        if(sizeof($procedimento->agenda) == 0){
            return $validacao[] = [
                'erro' => 'Horário',
                'texto' => 'Horário selecionado indisponível'
            ];
        }

        if(sizeof($procedimento->agenda) > 0){
            if(sizeof($procedimento->agenda[0]->agendamentos) > 0){
                return $validacao[] = [
                    'erro' => 'Horário',
                    'texto' => 'Horário selecionado indisponível'
                ];
            }
        }

        ///////////VERIFICA SE CONTEM DIA UNICO
        $agendaProcedimento = $this->getAgendaDia($procedimento->agenda, $dia_mes);

        if (!$agendaProcedimento) {
            return $validacao[] = [
                'erro' => 'Agenda',
                'texto' => 'Titular não atende nessa data'
            ];
        }

        ///////////VERIFICA SE PRESTADOR ATENDE NESSE HORARIO

        $dataAtendimento['hora_inicio'] = $this->retornaData($agendaProcedimento['hora_inicio'], $dadosFinalizarExame['data']);
        $dataAtendimento['hora_fim'] = $this->retornaData($agendaProcedimento['hora_fim'], $dadosFinalizarExame['data']);
        $dataAtendimento['hora_atendimento'] = $this->retornaData($agendaProcedimento['hora_inicio'], $dadosFinalizarExame['data']);
        $dataAtendimento['duracao_intervalo'] = $agendaProcedimento['duracao_intervalo'];
        $dataAtendimento['hora_intervalo'] = $this->retornaData($agendaProcedimento['hora_intervalo'], $dadosFinalizarExame['data']);
        $dataAtendimento['hora_escolhida_usuario'] = $this->retornaData($horarioAgenda);
        $dataAtendimento['tempo_atendimento'] = $agendaProcedimento['duracao_atendimento'];

        $dataAtendimento['totalAtendimentos'] = $this->getTotalAtendimento($dataAtendimento['hora_inicio'], $dataAtendimento['hora_fim'], $agendaProcedimento['duracao_atendimento']);

        $existeHorarioAtendimento = $this->existeHorarioAtendimento($dataAtendimento);

        if ($existeHorarioAtendimento == false) {
            return $validacao[] = [
                'erro' => 'Horário',
                'texto' => 'Procedimento não atende nesse horário'
            ];
        }



        ///////////VERIFICA SE REALIZA PROCEDIMENTO PELO CONVENIO
        $convenio = $procedimento->instituicaoProcedimentosConvenios()->where('convenios_id', $dadosFinalizarExame['convenioId'])->first();

        if (!$convenio) {
            return $validacao[] = [
                'erro' => 'Convênio',
                'texto' => 'Procedimento não atende pelo convênio selecionado'
            ];
        }

        $totalConsulta = $convenio->pivot->valor;

        if($dadosFinalizarExame['tipoPagamentoForm'] == 'cartao_entrega'){
            if(!$instituicao->cartao_entrega){
                return $validacao [] = [
                    'erro' => 'Tipo pagamento',
                    'texto' => 'Instituição não aceita forma de pagamento'
                ];
            }
        }
        
        if($dadosFinalizarExame['tipoPagamentoForm'] == 'dinheiro'){
            if(!$instituicao->dinheiro){
                return $validacao [] = [
                    'erro' => 'Tipo pagamento',
                    'texto' => 'Instituição não aceita forma de pagamento'
                ];
            }
        }

        if($dadosFinalizarExame['tipoPagamentoForm'] == 'cartao_credito'){
            ///VALIDAÇÃO PARCELAS
            if($dadosFinalizarExame['parcelas'] <= $instituicao->max_parcela && $dadosFinalizarExame['parcelas'] > 0 ){
                if($dadosFinalizarExame['parcelas'] > $instituicao->free_parcela){
    
                    $parcelasTaxa = $dadosFinalizarExame['parcelas'] - $instituicao->free_parcela;
                    $valorTaxa = $parcelasTaxa * $instituicao->valor_parcela;
    
                    $valorNovo = ($totalConsulta * ( 1 + ($valorTaxa / 100 ) ) );
                    $totalConsulta = number_format($valorNovo, 2);
                    $valorParcelas = $totalConsulta / $dadosFinalizarExame['parcelas'];
                    $valorParcelas = number_format($valorParcelas, 2);
    
                    if($valorParcelas < $instituicao->valor_minimo && $dadosFinalizarExame['parcelas'] != 1){
                        return $validacao[] = [
                            'erro' => 'Parcelas',
                            'texto' => 'Numero de parcelas inválido!',
                        ];
                    }
    
                }else{
                    $valorParcelas = $totalConsulta / $dadosFinalizarExame['parcelas'];
    
                    if($valorParcelas < $instituicao->valor_minimo && $dadosFinalizarExame['parcelas'] != 1){
                        return $validacao[] = [
                            'erro' => 'Parcelas',
                            'texto' => 'Numero de parcelas inválido!',
                        ];
                    }
                }
    
            }else{
                return $validacao[] = [
                    'erro' => 'Parcelas',
                    "texto" => "Numero de parcelas inválido!"
                ];
            }
    
            //////////VALIDAÇÃO CARTÃO USUARIO
            $enderecoCobranca = UsuarioEndereco::where('usuario_id',$request->user('sanctum')->id)->where('id', $dadosFinalizarExame['endereco'])->first();
    
            if(!empty($enderecoCobranca)){
                $endereco_cartao = [
                    'rua' => $enderecoCobranca->rua,
                    'numero' => $enderecoCobranca->numero,
                    'bairro' => $enderecoCobranca->bairro,
                    'cidade' => $enderecoCobranca->cidade,
                    'estado' => $enderecoCobranca->estado,
                    'complemento' => $enderecoCobranca->complemento,
                    'referencia' => $enderecoCobranca->referencia,
                    'cep' => $enderecoCobranca->cep,
                ];
                ///VALIDAÇÃO CARTÃO USUARIO
                if($dadosFinalizarExame['cartao']){
                    ///USAR CARTÃO EXISTENTE
                    $cartao = UsuarioCartao::where('usuario_id', $request->user('sanctum')->id)->where('id', $dadosFinalizarExame['cartao'])->first();
    
                    if(empty($cartao)){
                        return $validacao[] = [
                            'erro' => 'Cartão',
                            "texto" => "Cartão não existe!"
                        ];
                    }
    
    
                    $dados_cartao = [
                        'cvv' => $dadosFinalizarExame['cvv'],
                        'id_pagarme' => $cartao->id_pagarme,
                        'nome_cartao' => $cartao->nome,
                        'id' => $cartao->id
                    ];
                    $cartao_pagarme = array_merge($endereco_cartao, $dados_cartao);
                }else{
    
                    if($dadosFinalizarExame['numero_cartao'] && $dadosFinalizarExame['nome_cartao'] && $dadosFinalizarExame['data_validade']){
                        $dados_cartao = [
                            'numero_cartao' => $dadosFinalizarExame['numero_cartao'],
                            'nome_cartao' => $dadosFinalizarExame['nome_cartao'],
                            'data_validade' => $dadosFinalizarExame['data_validade'],
                            'cvv' => $dadosFinalizarExame['cvv']
                        ];
                        $cartao_pagarme = array_merge($endereco_cartao, $dados_cartao);
    
                    }else{
                        return $validacao[] = [
                            'erro' => 'Cartão',
                            "texto" => "Escolha um cartão existente!"
                        ];
                    }
    
                }
            }else{
                return $validacao[] = [
                    'erro' => 'Endereço',
                    "texto" => "Endereço de cobrança não existe!"
                ];
            }
        }


        $transacao = DB::transaction(function () use ($instituicao, $request, $totalConsulta, $dadosFinalizarExame, $cartao_pagarme, $horarioAgenda, $convenio){

            $agendamentos = [
                'tipo' => 'agendamento',
                'data' => $horarioAgenda,
                'status' => 'pendente',
                'valor_total' => $totalConsulta,
                'parcelas' => $dadosFinalizarExame['parcelas'],
                'porcento_parcela' => $instituicao->valor_parcela,
                'free_parcelas' => $instituicao->free_parcela,
                'instituicoes_agenda_id' => $dadosFinalizarExame['agendaId'],
                'usuario_id' => $request->user('sanctum')->id,
                'cartao_id' => $dadosFinalizarExame['cartao'],
                'forma_pagamento' => $dadosFinalizarExame['tipoPagamentoForm'],
            ];

            $agendamento = Agendamentos::create($agendamentos);

            $agendamentoProcedimento = [
                'procedimentos_instituicoes_convenios_id' => $convenio->pivot->id,
                'valor_atual' => $convenio->pivot->valor,
            ];

            $agendamento->agendamentoProcedimento()->create($agendamentoProcedimento);

            if($dadosFinalizarExame['tipoPagamentoForm'] == 'cartao_credito'){
                $pagarMe = new PagarMe();
                return $pagarMe->criarTransacaoAgendaExame($agendamento, $instituicao, $cartao_pagarme);
            }

            return (object) [
                'status' => 'sucesso',
                'texto' => 'Agendamento realizado'
            ];
            

        });

        if(property_exists($transacao,'error')){
            return $validacao[] = [
                'erro' => 'Transação',
                'texto' => 'Ocorreu um erro na transação, tente novamente!'
            ];

        }

        if($transacao->status == 'refused'){
            $resultado = MotivoRecusaPagarme($transacao);
            return $validacao[] =
            [
                'texto' => $resultado['msg'],
                'status' => $transacao->status,
                'descricao' => $resultado['orientacao']
            ];
        }else{

            return $validacao[] =
            [
                'texto' => 'Agendamento realizado',
                'status' => $transacao->status
            ];
        }


    }

    private function retornaData($tipo, $dataEscolhida = null)
    {
        $data = $tipo;
        if($dataEscolhida){
            $data = $dataEscolhida.' '.$tipo;
        }
        $hora = new DateTime($data);

        return $hora;
    }

    private function getTotalAtendimento($inicio, $fim, $atendimento)
    {
        $intervalo = $inicio->diff($fim);
        $horas = $intervalo->h;
        $minutos = $intervalo->i;

        $horasParaMinutos = 0;

        if($horas > 0){
            $horasParaMinutos = $horas * 60;
        }

        $minutosTotal = 0;

        if($minutos > 0){
            $minutosTotal = $minutos + $horasParaMinutos;
        }else{
            $minutosTotal = $horasParaMinutos;
        }

        $tempoAtendimento = explode(':', $atendimento);
        $atendimentoEmMinutos = ($tempoAtendimento[0]*60) + ($tempoAtendimento[1]);

        $totalAtendimentos = $minutosTotal/$atendimentoEmMinutos;

        return $totalAtendimentos;
    }

    private function existeHorarioAtendimento($dataAtendimento)
    {
        $tempoAtendimento = explode(':', $dataAtendimento['tempo_atendimento']);
        $atendimentoEmMinutos = ($tempoAtendimento[0]*60) + ($tempoAtendimento[1]);
        $verificaIntervalo = 0;

        for ($i=0; $i < $dataAtendimento['totalAtendimentos']; $i++) {
            if($i == 0){
                if($dataAtendimento['hora_inicio'] == $dataAtendimento['hora_escolhida_usuario']){
                    return true;
                }
            }else{

                if($verificaIntervalo == 0){
                    $dataAtendimento['hora_atendimento']->add(new DateInterval('PT'.$tempoAtendimento[0].'H'.$tempoAtendimento[1].'M'));

                    $verificaFinal = $dataAtendimento['hora_atendimento']->diff($dataAtendimento['hora_intervalo']);

                    $horasParaMinutos = 0;
                    $horas = $verificaFinal->h;
                    $minutos = $verificaFinal->i;
                    if($horas > 0){
                        $horasParaMinutos = $horas * 60;
                    }

                    $minutosTotal = 0;

                    if($minutos > 0){
                        $minutosTotal = $minutos + $horasParaMinutos;
                    }else{
                        $minutosTotal = $horasParaMinutos;
                    }

                    if($minutosTotal < $atendimentoEmMinutos){

                        $horaIntervalo = explode(':', $dataAtendimento['duracao_intervalo']);
                        $dataAtendimento['hora_intervalo']->add(new DateInterval('PT'.$horaIntervalo[0].'H'.$horaIntervalo[1].'M'));
                        $dataAtendimento['hora_atendimento'] = $dataAtendimento['hora_intervalo'];
                        $verificaIntervalo = 1;

                    }else{
                        if($dataAtendimento['hora_atendimento'] == $dataAtendimento['hora_escolhida_usuario']){
                            return true;
                        }
                    }
                }else{

                    $verificaFinal = $dataAtendimento['hora_atendimento']->diff($dataAtendimento['hora_fim']);

                    $horasParaMinutos = 0;
                    $horas = $verificaFinal->h;
                    $minutos = $verificaFinal->i;
                    if($horas > 0){
                        $horasParaMinutos = $horas * 60;
                    }

                    $minutosTotal = 0;

                    if($minutos > 0){
                        $minutosTotal = $minutos + $horasParaMinutos;
                    }else{
                        $minutosTotal = $horasParaMinutos;
                    }

                    if($minutosTotal < $atendimentoEmMinutos){

                        return false;

                    }else{
                        if($dataAtendimento['hora_atendimento'] == $dataAtendimento['hora_escolhida_usuario']){
                            return true;
                        }
                    }

                    $dataAtendimento['hora_atendimento']->add(new DateInterval('PT'.$tempoAtendimento[0].'H'.$tempoAtendimento[1].'M'));
                }

            }
        }
    }

    private function getAgendaDia($agenda, $dia_mes)
    {
        $prestador = null;
        foreach ($agenda as $key => $value) {
            if ($value->dias_unicos) {
                $diasUnicos = $value->dias_unicos;
                $diasUnicos = json_decode($diasUnicos);
                foreach ($diasUnicos as $keyUnico => $dUnico) {
                    if ($dUnico->date == $dia_mes) {
                        $agendaUnico = [
                            'id' => $value->id,
                            'hora_inicio' => $dUnico->hora_inicio,
                            'hora_fim' => $dUnico->hora_fim,
                            'hora_intervalo' => $dUnico->hora_intervalo,
                            'duracao_intervalo' => $dUnico->duracao_intervalo,
                            'duracao_atendimento' => $dUnico->duracao_atendimento,
                        ];
                        $prestador = $agendaUnico;
                    }
                }
            } else {
                if (!$prestador) {
                    $agendaUnico = [
                        'id' => $value->id,
                        'hora_inicio' => $value->hora_inicio,
                        'hora_fim' => $value->hora_fim,
                        'hora_intervalo' => $value->hora_intervalo,
                        'duracao_intervalo' => $value->duracao_intervalo,
                        'duracao_atendimento' => $value->duracao_atendimento,
                    ];
                    $prestador = $agendaUnico;
                }
            }

        }

        if (!$prestador) {
            return null;
        }

        return $prestador;
    }

}
