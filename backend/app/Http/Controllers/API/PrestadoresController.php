<?php

namespace App\Http\Controllers\API;

use App\Agendamentos;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConveniosCollection;
use App\Http\Resources\PrestadoresCollection;
use App\Http\Resources\PrestadorResource;
use App\Instituicao;
use App\InstituicoesPrestadores;
use App\Libraries\PagarMe;
use App\Procedimento;
use App\UsuarioCartao;
use App\UsuarioEndereco;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrestadoresController extends Controller
{
    public function getPrestadores(Request $request)
    {
        $dia_semana = date('w', strtotime($request->filters['data']));
        $dia_semana = $this->convertDiaSemana($dia_semana);
        $dia_mes = date('d/m/Y', strtotime($request->filters['data']));

        $instituicaoPrestadores = InstituicoesPrestadores::where('especialidades_id', $request->filters['especialidade'])->where('instituicoes_id', $request->filters['instituicaoId'])->with(['agenda' => function ($query) use($dia_semana, $dia_mes, $request) {
            $query->where('referente', 'prestador');
            $query->where(function($q) use($dia_semana, $dia_mes) {
                $q->orWhere('dias_continuos', $dia_semana);
                $q->orWhere(function($query) use($dia_mes){
                    $query->whereJsonContains("dias_unicos", ['date' => $dia_mes]);
                });
            });
            $query->with(['agendamentos' => function ($queryAgenda) use($request) {
                $queryAgenda->whereDate('data', $request->filters['data']);
                $queryAgenda->where('status', '!=', 'cancelado');
            }]);
        }, 'prestador'])->get();

        $prestadores = [];

        if($instituicaoPrestadores){

            foreach ($instituicaoPrestadores as $key => $value) {
                if (sizeof($value->agenda) == 0) {
                    continue;
                }
                    $auxiliar = clone $value->prestador;

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
                                    $auxiliar['agenda'] = $agendaUnico;

                                    if ($agenda->agendamentos) {
                                        $auxiliar['agendamentos'] = $agenda->agendamentos;
                                    }else{
                                        $auxiliar['agendamentos'] = null;
                                    }
                                }
                            }
                        } else {
                            if (!$auxiliar['agenda']) {
                                $agendaUnico = [
                                    'id' => $agenda->id,
                                    'hora_inicio' => $agenda->hora_inicio,
                                    'hora_fim' => $agenda->hora_fim,
                                    'hora_intervalo' => $agenda->hora_intervalo,
                                    'duracao_intervalo' => $agenda->duracao_intervalo,
                                    'duracao_atendimento' => $agenda->duracao_atendimento,
                                ];
                                $auxiliar['agenda'] = $agendaUnico;

                                if ($agenda->agendamentos) {
                                    $auxiliar['agendamentos'] = $agenda->agendamentos;
                                }else{
                                    $auxiliar['agendamentos'] = null;
                                }
                            }
                        }

                    }
                    array_push($prestadores, $auxiliar);
            }
        }

        if(!$prestadores)
        {
            return [
                'data' => null
            ];
        }

        return new PrestadoresCollection($prestadores);
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

    public function prestador(Request $request)
    {

        $dia_semana = date('w', strtotime($request->filtros['dataAgenda']));
        $dia_semana = $this->convertDiaSemana($dia_semana);
        $dia_mes = date('d/m/Y', strtotime($request->filtros['dataAgenda']));

        $instituicaoPrestadores = InstituicoesPrestadores::where('especialidades_id', $request->filtros['especialidade'])->where('instituicoes_id', $request->filtros['instituicaoId'])->where('prestadores_id', $request->filtros['prestadorId'])->with(['agenda' => function ($query) use($dia_semana, $dia_mes, $request) {
            $query->where('referente', 'prestador');
            $query->where(function($q) use($dia_semana, $dia_mes) {
                $q->orWhere('dias_continuos', $dia_semana);
                $q->orWhere(function($query) use($dia_mes){
                    $query->whereJsonContains("dias_unicos", ['date' => $dia_mes]);
                });
            });
            $query->with(['agendamentos' => function ($queryAgenda) use($request) {
                $queryAgenda->whereDate('data', $request->filtros['dataAgenda']);
                $queryAgenda->where('status', '!=', 'cancelado');
            }]);
        }, 'prestador', 'prestadoresProcedimentos' => function($qProcedimentos) {
            $qProcedimentos->with(['procedimentos']);
        }])->first();

        $prestador = null;
        if($instituicaoPrestadores){

            if (sizeof($instituicaoPrestadores->agenda) > 0) {
                $prestador = clone $instituicaoPrestadores->prestador;
                $prestador['prestadoresProcedimentos'] = $instituicaoPrestadores->prestadoresProcedimentos;

                foreach ($instituicaoPrestadores->agenda as $keyAgenda => $agenda) {
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
                                $prestador['agenda'] = $agendaUnico;

                                if ($agenda->agendamentos) {
                                    $prestador['agendamentos'] = $agenda->agendamentos;
                                }else{
                                    $prestador['agendamentos'] = null;
                                }
                            }
                        }
                    } else {
                        if (!$prestador['agenda']) {
                            $agendaUnico = [
                                'id' => $agenda->id,
                                'hora_inicio' => $agenda->hora_inicio,
                                'hora_fim' => $agenda->hora_fim,
                                'hora_intervalo' => $agenda->hora_intervalo,
                                'duracao_intervalo' => $agenda->duracao_intervalo,
                                'duracao_atendimento' => $agenda->duracao_atendimento,
                            ];
                            $prestador['agenda'] = $agendaUnico;

                            if ($agenda->agendamentos) {
                                $prestador['agendamentos'] = $agenda->agendamentos;
                            }else{
                                $prestador['agendamentos'] = null;
                            }
                        }
                    }

                }
            }
        }

        return new PrestadorResource($prestador);
    }

    public function getConveniosProcedimentoPrestador(Request $request)
    {
        $procedimentoId = $request->procedimentoId;
        $instituicaoId = $request->instituicaoId;

        $procedimento = Procedimento::where('id',$procedimentoId)->with(['procedimentoInstituicao' => function($query) use($instituicaoId){
            $query->where('instituicoes_id', $instituicaoId);
            $query->with(['instituicaoProcedimentosConvenios']);
        }])->first();

        $convenios = $procedimento->procedimentoInstituicao[0]->instituicaoProcedimentosConvenios;

        return new ConveniosCollection($convenios);
    }

    public function finalizarConsulta(Request $request)
    {

        $dadosFinalizarConsulta = $request->form;
        $validacao = [];
        $cartao_pagarme = [];

        ///////////BUSCA INSTITUICAÇÃO
        $instituicao = Instituicao::find($dadosFinalizarConsulta['instituicaoId']);

        if(!$instituicao){
            return $validacao[] = [
                'erro' => 'Instituição',
                'texto' => 'Instituição não existe'
            ];
        }

        ///////////CRIAR VARIAVEL DO HORARIO ESCOLHIDO
        $horarioAgenda = $dadosFinalizarConsulta['data'].' '.$dadosFinalizarConsulta['horario_agendado'].':00';
        $horarioAgenda = date('Y-m-d H:i:s', strtotime($horarioAgenda));

        //////////VALIDAÇÃO DE PROCEDIMENTOS PERTENCE A INSTITUIÇÃO
        $procedimento = $instituicao->procedimentos()->where('procedimentos_id', $dadosFinalizarConsulta['procedimento'])->first();
        if (!$procedimento) {
            return $validacao[] = [
                'erro' => 'Procedimento',
                'texto' => 'Procedimento não realizado por esta instituição'
            ];
        }

        //////////VALIDAÇÃO DE PRESTADOR PERTENCE A INSTITUIÇÃO E PEGA AGENDA DO PRESTADOR
        $dia_mes = date('d/m/Y', strtotime($dadosFinalizarConsulta['data']));

        $prestador = $instituicao->prestadoresEspecialidades()->where('prestadores_id', $dadosFinalizarConsulta['prestadorId'])->where('especialidades_id', $dadosFinalizarConsulta['especialidadeId'])->with(['agenda' => function($query) use($dadosFinalizarConsulta, $horarioAgenda){
            $query->where('id', $dadosFinalizarConsulta['agendaId']);
            $query->with(['agendamentos' => function($qAgendamentos) use($horarioAgenda, $dadosFinalizarConsulta){
                $qAgendamentos->where('instituicoes_agenda_id', $dadosFinalizarConsulta['agendaId']);
                $qAgendamentos->where('data', $horarioAgenda);
                $qAgendamentos->where('status', '!=', 'cancelado');
            }]);

        }])->first();

        if (!$prestador) {
            return $validacao[] = [
                'erro' => 'Prestador',
                'texto' => 'Prestador não atende por esta instituição'
            ];
        }

        ///////////VERIFICAR SE HORARIO ESTA DISPONIVEL
        if(sizeof($prestador->agenda) == 0){
            return $validacao[] = [
                'erro' => 'Horário',
                'texto' => 'Horário selecionado indisponível'
            ];
        }

        if(sizeof($prestador->agenda) > 0){
            if(sizeof($prestador->agenda[0]->agendamentos) > 0){
                return $validacao[] = [
                    'erro' => 'Horário',
                    'texto' => 'Horário selecionado indisponível'
                ];
            }
        }

        ///////////VERIFICA SE CONTEM DIA UNICO
        $agendaPrestador = $this->getAgendaDia($prestador->agenda, $dia_mes);

        if (!$agendaPrestador) {
            return $validacao[] = [
                'erro' => 'Agenda',
                'texto' => 'Titular não atende nessa data'
            ];
        }

        ///////////VERIFICA SE PRESTADOR ATENDE NESSE HORARIO
        $dataAtendimento['hora_inicio'] = $this->retornaData($agendaPrestador['hora_inicio'], $dadosFinalizarConsulta['data']);
        $dataAtendimento['hora_fim'] = $this->retornaData($agendaPrestador['hora_fim'], $dadosFinalizarConsulta['data']);
        $dataAtendimento['hora_atendimento'] = $this->retornaData($agendaPrestador['hora_inicio'], $dadosFinalizarConsulta['data']);
        $dataAtendimento['duracao_intervalo'] = $agendaPrestador['duracao_intervalo'];
        $dataAtendimento['hora_intervalo'] = $this->retornaData($agendaPrestador['hora_intervalo'], $dadosFinalizarConsulta['data']);
        $dataAtendimento['hora_escolhida_usuario'] = $this->retornaData($horarioAgenda);
        $dataAtendimento['tempo_atendimento'] = $agendaPrestador['duracao_atendimento'];

        $dataAtendimento['totalAtendimentos'] = $this->getTotalAtendimento($dataAtendimento['hora_inicio'], $dataAtendimento['hora_fim'], $agendaPrestador['duracao_atendimento']);
        $existeHorarioAtendimento = $this->existeHorarioAtendimento($dataAtendimento);
        if ($existeHorarioAtendimento == false) {
            return $validacao[] = [
                'erro' => 'Horário',
                'texto' => 'Prestador não atende nesse horário'
            ];
        }

        ///////////VERIFICA SE PRESTADOR REALIZA PROCEDIMENTO PELO CONVENIO
        $convenio = $prestador->prestadoresProcedimentos()->where('procedimentos_id', $procedimento->id)->whereHas('procedimentos_convenios', function($query) use($dadosFinalizarConsulta){
            $query->where('convenios_id', $dadosFinalizarConsulta['convenio']);
        })->first();

        if (!$convenio) {
            return $validacao[] = [
                'erro' => 'Convênio',
                'texto' => 'Prestador não realiza este procedimento pelo convênio selecionado'
            ];
        }

        //////////VALIDAÇÃO DE PROCEDIMENTOS PERTENCE A CONVENIO INSTITUICAO
        $convenioValor = $procedimento->procedimentoInstituicao()->with(['instituicaoProcedimentosConvenios' => function($query) use($dadosFinalizarConsulta){
            $query->where('convenios_id', $dadosFinalizarConsulta['convenio']);
        }])->first();

        if (!$convenioValor->instituicaoProcedimentosConvenios) {
            return $validacao[] = [
                'erro' => 'Convênio',
                'texto' => 'Procedimento não realizado por este convênio'
            ];
        }

        $totalConsulta = $convenioValor->instituicaoProcedimentosConvenios[0]->pivot->valor;

        if($dadosFinalizarConsulta['tipoPagamentoForm'] == 'cartao_entrega'){
            if(!$instituicao->cartao_entrega){
                return $validacao [] = [
                    'erro' => 'Tipo pagamento',
                    'texto' => 'Instituição não aceita forma de pagamento'
                ];
            }
        }
        
        if($dadosFinalizarConsulta['tipoPagamentoForm'] == 'dinheiro'){
            if(!$instituicao->dinheiro){
                return $validacao [] = [
                    'erro' => 'Tipo pagamento',
                    'texto' => 'Instituição não aceita forma de pagamento'
                ];
            }
        }

        if($dadosFinalizarConsulta['tipoPagamentoForm'] == 'cartao_credito'){
            ///VALIDAÇÃO PARCELAS
            if($dadosFinalizarConsulta['parcelas'] <= $instituicao->max_parcela && $dadosFinalizarConsulta['parcelas'] > 0 ){
                if($dadosFinalizarConsulta['parcelas'] > $instituicao->free_parcela){
    
                    $parcelasTaxa = $dadosFinalizarConsulta['parcelas'] - $instituicao->free_parcela;
                    $valorTaxa = $parcelasTaxa * $instituicao->valor_parcela;
    
                    $valorNovo = ($totalConsulta * ( 1 + ($valorTaxa / 100 ) ) );
                    $totalConsulta = number_format($valorNovo, 2);
                    $valorParcelas = $totalConsulta / $dadosFinalizarConsulta['parcelas'];
                    $valorParcelas = number_format($valorParcelas, 2);
    
                    if($valorParcelas < $instituicao->valor_minimo && $dadosFinalizarConsulta['parcelas'] != 1){
                        return $validacao[] = [
                            'erro' => 'Parcelas',
                            'texto' => 'Numero de parcelas inválido!',
                        ];
                    }
    
                }else{
                    $valorParcelas = $totalConsulta / $dadosFinalizarConsulta['parcelas'];
    
                    if($valorParcelas < $instituicao->valor_minimo && $dadosFinalizarConsulta['parcelas'] != 1){
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
            $enderecoCobranca = UsuarioEndereco::where('usuario_id',$request->user('sanctum')->id)->where('id', $dadosFinalizarConsulta['endereco'])->first();
    
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
                if($dadosFinalizarConsulta['cartao']){
                    ///USAR CARTÃO EXISTENTE
                    $cartao = UsuarioCartao::where('usuario_id', $request->user('sanctum')->id)->where('id', $dadosFinalizarConsulta['cartao'])->first();
    
                    if(empty($cartao)){
                        return $validacao[] = [
                            'erro' => 'Cartão',
                            "texto" => "Cartão não existe!"
                        ];
                    }
    
    
                    $dados_cartao = [
                        'cvv' => $dadosFinalizarConsulta['cvv'],
                        'id_pagarme' => $cartao->id_pagarme,
                        'nome_cartao' => $cartao->nome,
                        'id' => $cartao->id
                    ];
                    $cartao_pagarme = array_merge($endereco_cartao, $dados_cartao);
                }else{
    
                    if($dadosFinalizarConsulta['numero_cartao'] && $dadosFinalizarConsulta['nome_cartao'] && $dadosFinalizarConsulta['data_validade']){
                        $dados_cartao = [
                            'numero_cartao' => $dadosFinalizarConsulta['numero_cartao'],
                            'nome_cartao' => $dadosFinalizarConsulta['nome_cartao'],
                            'data_validade' => $dadosFinalizarConsulta['data_validade'],
                            'cvv' => $dadosFinalizarConsulta['cvv']
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


        $transacao = DB::transaction(function () use ($instituicao, $request, $totalConsulta, $dadosFinalizarConsulta, $cartao_pagarme, $horarioAgenda, $convenioValor){

            $agendamentos = [
                'tipo' => 'agendamento',
                'data' => $horarioAgenda,
                'status' => 'pendente',
                'valor_total' => $totalConsulta,
                'parcelas' => $dadosFinalizarConsulta['parcelas'],
                'porcento_parcela' => $instituicao->valor_parcela,
                'free_parcelas' => $instituicao->free_parcela,
                'instituicoes_agenda_id' => $dadosFinalizarConsulta['agendaId'],
                'usuario_id' => $request->user('sanctum')->id,
                'cartao_id' => $dadosFinalizarConsulta['cartao'],
                'forma_pagamento' => $dadosFinalizarConsulta['tipoPagamentoForm'],
            ];

            $agendamento = Agendamentos::create($agendamentos);

            $agendamentoProcedimento = [
                'procedimentos_instituicoes_convenios_id' => $convenioValor->instituicaoProcedimentosConvenios[0]->pivot->id,
                'valor_atual' => $convenioValor->instituicaoProcedimentosConvenios[0]->pivot->valor,
            ];

            $agendamento->agendamentoProcedimento()->create($agendamentoProcedimento);

            if($dadosFinalizarConsulta['tipoPagamentoForm'] == 'cartao_credito'){
                $pagarMe = new PagarMe();
                return $pagarMe->criarTransacaoAgendaConsulta($agendamento, $instituicao, $cartao_pagarme);
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
