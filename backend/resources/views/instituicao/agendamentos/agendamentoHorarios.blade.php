<div class="agenda_horario_row row @if(\Carbon\Carbon::now() >= $i &&  (\Carbon\Carbon::now() < \Carbon\Carbon::parse($i)->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $agendaDados[$x]['duracao_atendimento'])) && \Carbon\Carbon::now() < \Carbon\Carbon::createFromFormat('d/m/Y', $data)->setTimeFromTimeString($agendaDados[$x]['hora_intervalo'])) ) is_current @endif @if (strtotime($i->format('H:i')) < strtotime("12:59")) class_am @else class_pm @endif" >
    <div class="agenda_horario @if(\Carbon\Carbon::parse($i)->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $agendaDados[$x]['duracao_atendimento'])) < \Carbon\Carbon::now()) horario_passado @endif" >
        <strong sty>{{$i->format('H:i')}}</strong>
    </div>

    <div class="agenda_eventos row col-md ">
        @php $agendamentos_count = 0; @endphp
        @foreach ( $agendamentos_controle as $key => $agendamento)
        @php
            $exige_card_aut = false;
            foreach ($agendamento->agendamentoProcedimento as $k => $v) {
                if($v->procedimentoInstituicaoConvenio->utiliza_parametro_convenio == 0){
                    if($v->procedimentoInstituicaoConvenio->carteirinha_obrigatoria || $v->procedimentoInstituicaoConvenio->aut_obrigatoria){
                        $exige_card_aut = true;
                        break;
                    }
                }else if($v->procedimentoInstituicaoConvenio->convenios->carteirinha_obg || $v->procedimentoInstituicaoConvenio->convenios->aut_obrigatoria){
                    $exige_card_aut = true;
                    break;
                }
            }
        @endphp

        @if ( \Carbon\Carbon::parse($agendamento['data']) < $i || (\Carbon\Carbon::parse($agendamento['data']) >= $i &&  \Carbon\Carbon::parse($agendamento['data']) < \Carbon\Carbon::parse($i)->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $agendaDados[$x]['duracao_atendimento']))) )
                @php
                    $totalAgendamentos++;
                @endphp
                {{-- {{\Carbon\Carbon::parse($agendamento['data']) }} --}}
                @php unset($agendamentos_controle[$key]);$agendamentos_count++; @endphp
                {{-- {{$agendamentos_count}} --}}

                <div class="agenda_eventos agenda_eventos_row_horario col-md-11 p-0 @if ($agendamento['id_referente'] != null) horario_cancelado_nao_exibir @endif" @if($exige_card_aut && empty($agendamento->agendamentoGuias[0]['cod_aut_convenio']) && !in_array($agendamento['status'], ['cancelado','ausente'] ) ) style="border-style: solid; border-width: 0px 0px 5px 0px; border-color: #f19696;" @endif>
                    <div class="agendamento
                    @if (count($agendamento->atendimento) > 0 && $agendamento->atendimento[0]->status == 1 && $agendamento->status == "agendado")
                        status-6 clickable
                    {{-- @elseif( ($agendamento['status']=='pendente' || $agendamento['status']=='agendado') && (\Carbon\Carbon::parse($agendamento['data']) <> $i || $agendamentos_count>1))
                        status-0 clickable --}}
                    @elseif( $agendamento['status']=='pendente' )
                        status-1 clickable
                    @elseif( $agendamento['status']=='agendado')
                        status-2 clickable
                    @elseif( $agendamento['status']=='confirmado')
                        status-3 clickable
                    @elseif($agendamento['status']=='cancelado')
                        status-4 @if ($agendamento['pessoa_id']!=null)
                            clickable
                        @endif
                    @elseif( $agendamento['status']=='finalizado')
                        status-5 clickable
                    @elseif( $agendamento['status']=='ausente')
                        status-7 clickable
                    @elseif( $agendamento['status']=='em_atendimento')
                        status-8 clickable
                    @elseif( $agendamento['status']=='finalizado_medico')
                        status-9 clickable
                    @elseif( $agendamento['status']=='desistencia')
                        status-10 clickable
                    @endif
                    " data-agendamento="{{$agendamento['id']}}">
                        @if($agendamento['status']=='cancelado' && $agendamento['usuario_id']==null && $agendamento['pessoa_id'] === null)
                            <div class="agenda_eventos">

                                <div class="agendamento agendamento_past">
                                    <div class="agendamento_col agendamento_texto">
                                    Horário cancelado {{$agendamento->motivo_cancelamento? '( Motivo: '.$agendamento->motivo_cancelamento.' )':'' }}
                                    </div>
                                </div>
                            </div>
                        @else
                        
                        <div class="agendamento_col agendamento-icone" @if($agendamento->compromisso_id) data-toggle="tooltip" title="" data-original-title="{{$agendamento->compromisso->descricao}}" @endif @if ($agendamento->compromisso_id) style="border-left: 20px solid {{$agendamento->compromisso->cor}}!important; border-radius: 5px; margin-right: 10px;" @endif>

                            {{-- Teleatendimento --}}
                            @if($agendamento->teleatendimento == 1)
                            <span style="font-size: 14px;" data-toggle="tooltip" title="" data-original-title="Teleatendimento" class="mdi mdi-laptop-mac"></span> &nbsp;
                            @endif
                            {{-- Fim Teleatendimento --}}

                            @if ($agendamento->data_original)
                                <span data-toggle="tooltip" title="" data-original-title="A hora original do atendimento é: {{\Carbon\Carbon::parse($agendamento['data_original'])->format('H:i')}}" class="fa fa-exclamation help text-warning" ></span>
                            @endif
                            @if( ($agendamento['status']=='pendente' || $agendamento['status']=='agendado') && (\Carbon\Carbon::parse($agendamento['data']) <> $i ) && $maisAgenda == false)
                            <span data-toggle="tooltip" title="" data-original-title="É necessário corrigir o horário do atendimento. Horário inválido : {{\Carbon\Carbon::parse($agendamento['data'])->format('H:i')}}" class="fa fa-exclamation help text-warning" ></span>
                            @elseif($agendamentos_count>1)
                            {{-- <span data-toggle="tooltip" title="" data-original-title="Choque do horário do atendimento." class="fa fa-exclamation help text-warning" ></span> --}}
                            @endif

                            @if( $agendamento['status'] == 'desistencia' )
                            <span data-toggle="tooltip" title="" data-original-title="Paciente desistiu do atendimento" class="mdi mdi-account-off icon-agenda"></span>
                            @elseif (count($agendamento->atendimento) > 0 && $agendamento->atendimento[0]->status == 1)
                            <span data-toggle="tooltip" title="" data-original-title="Paciente em atendimento" class="mdi mdi-account-convert icon-agenda"></span>
                            @elseif( $agendamento['status']=='pendente' )
                            
                            @if ($agendamento->tipo_agenda == "encaixe")
                            <span data-toggle="tooltip" title="" data-original-title="Nenhuma confirmação do paciente ou clínica foi realizado" class="mdi mdi-account-plus icon-agenda"></span>
                            @else
                            <span data-toggle="tooltip" title="" data-original-title="Nenhuma confirmação do paciente ou clínica foi realizado" class="mdi mdi-account-alert icon-agenda"></span>
                            @endif
                            

                            @if ($agendamento['resposta_confirmacao_whatsapp']=='Remarcar')
                            &nbsp;<span data-toggle="tooltip" title="" data-original-title="Solicitado remarcação pelo whatsapp" class="fab fa-whatsapp icon-agenda"></span>
                            @endif

                            @elseif($agendamento['status']=='agendado')
                            <span data-toggle="tooltip" title="" data-original-title="A clínica confirmou o horário para o paciente" class="mdi mdi-account-convert icon-agenda"></span>
                            @elseif( $agendamento['status']=='confirmado' )
                            <span data-toggle="tooltip" title="" data-original-title="O paciente confirmou que estará presente" class="mdi mdi-account-check icon-agenda"></span>

                            @if ($agendamento['resposta_confirmacao_whatsapp']=='Confirmar')
                            &nbsp;<span data-toggle="tooltip" title="" data-original-title="Confirmato automático pelo whatsapp" class="fab fa-whatsapp icon-agenda"></span>
                            @endif

                            @elseif( $agendamento['status']=='cancelado' )
                            <span data-toggle="tooltip" title="" data-original-title="O agendamento foi cancelado" class="far fa-frown"></span>
                            @elseif( $agendamento['status']=='ausente' )
                            <span data-toggle="tooltip" title="" data-original-title="O paciente não compareceu ao agendamento" class="mdi mdi-account-remove icon-agenda"></span>
                            @elseif( $agendamento['status']=='finalizado' )
                            <span data-toggle="tooltip" title="" data-original-title="O agendamento foi finalizado" class="mdi mdi-checkbox-marked-circle-outline icon-agenda"></span>
                            @endif
                        </div>
                        <div class="agendamento_col agendamento-paciente">

                            @if ($agendamento->tipo_agenda == "encaixe")
                                <span style="font-weight: bold">({{($agendamento->data_original) ? date('H:i', strtotime($agendamento->data_original)): date('H:i', strtotime($agendamento->data))}})</span>                           
                            @elseif( ($agendamento['status']=='pendente' || $agendamento['status']=='agendado') && (\Carbon\Carbon::parse($agendamento['data']) <> $i ) && $maisAgenda == false)
                                <span style="font-weight: bold">({{($agendamento->data_original) ? date('H:i', strtotime($agendamento->data_original)): date('H:i', strtotime($agendamento->data))}})</span>
                            @endif
                            <span  style="font-weight:bold;"> <i class="fa fa-user"></i></span>
                            <span style="font-weight:bold;"> @if ($agendamento->usuario)
                                {{strtoupper ($agendamento->usuario->nome)}}
                                @else
                                {{strtoupper ($agendamento->pessoa->nome)}}
                            @endif</span>

                            {{-- DATA DE NASCIMENTO --}}
                            @if ($agendamento->pessoa->nascimento)
                            @php

                            $idade_paciente = \Carbon\Carbon::parse($agendamento->pessoa->nascimento)->age;

                            @endphp
                            <span style="font-weight:500;">
                                ({{$idade_paciente}} anos)
                            </span>
                            @endif

                            <span style="font-weight:bold;">-</span>
                            <span style="font-weight:500;"> <i class="fa fa-phone"></i></span>
                            <span style="font-weight:500;"> @if ($agendamento->usuario)
                                {{strtoupper ($agendamento->usuario->telefone)}}
                                @else
                                {{strtoupper ($agendamento->pessoa->telefone1)}}
                            @endif</span>
                            @if ($prestador_especialidade_id == "")
                                <span style="font-weight:bold;float: right">{{strtoupper ($agendamento->instituicoesAgenda->prestadores->prestador->nome)}}  </span>
                                <span  style="font-weight:bold;float: right"> <i class="fa fa-user-md"></i></span>
                            @endif
                        </div>
                        <div class="agendamento_col agendamento-procedimentos">
                            @if($agendamento->agendamentoProcedimento->count() > 0)
                                {{-- {{$agendamento}} --}}
                                @if($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo=='consulta')
                                    <span data-toggle="tooltip" title="" data-original-title="Exame" style="font-weight:bold;"> <i class="mdi mdi-stethoscope"></i></span>
                                @elseif($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo=='exame')
                                    <span data-toggle="tooltip" title="" data-original-title="Consulta" style="font-weight:bold;"> <i class="mdi mdi-clipboard-text"></i></span>
                                @endif
                                <span data-toggle="tooltip" title="" data-original-title="Convênio: {{$agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->convenios->nome}}" style="font-weight:bold;">
                                    {!!strtoupper(mb_substr($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->convenios->nome, 0, 21))!!} @if(strlen($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->convenios->nome > 20)) ... @endif
                                </span>
                                <span style="font-weight:bold;">-</span>

                                <span data-toggle="tooltip" title="" data-original-title="Procedimento: {{strtoupper($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->descricao)}}" style="font-weight:bold;">
                                
                                @php

                                    $text = $agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->descricao;                                
                                @endphp
                                    {!! mb_substr($text, 0, 21) !!} @if(strlen($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->convenios->nome) > 21) ... @endif                                
                                </span>
                                
                                @if (count($agendamento->agendamentoProcedimento) > 1)
                                    @php
                                        $texto = "";
                                        foreach ($agendamento->agendamentoProcedimento as $key => $value) {
                                            $texto .=  $value->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->descricao.", ";
                                        }
                                    @endphp

                                    <span class="" style="font-weight:bold;" data-html="true" data-toggle="tooltip" data-placement="right" title="" data-original-title="{{mb_substr($texto, 0, -2)}}">(+{{count($agendamento->agendamentoProcedimento)-1}} Exame)</span>
                                @endif

                                {{-- <span>
                                    @php if(!empty($agendamento->setor)) echo $agendamento->setor->descricao @endphp
                                </span> --}}
                            @endif

                        </div>
                        <div style="font-size: 14px;padding: 3px;">
                            @if(!empty($agendamento->setor))
                            <span>
                                <i class="mdi mdi-home-map-marker"></i> {{$agendamento->setor->descricao}}
                            </span>
                            @endif
                        </div>

                        <div class="agendamento_col agendamento_actions">
                            @if($agendamento->status == "agendado")
                                <span data-toggle="tooltip" data-placement="top" data-original-title="Tempo de espera do paciente">{{APP\Agendamentos::timeEspera($agendamento->atendimento[0]->data_hora)}}</span>
                            @endif
                            <div class="btn-group">
                                @if(count($this->usuario_prestador) > 0 && $this->usuario_prestador[0]->tipo == 2 && $agendamento->status != 'em_atendimento')
                                    @can('habilidade_instituicao_sessao', 'abrir_prontuario')
                                        <a href="{{ route('instituicao.pessoas.abrirProntuarioResumo', [$agendamento->pessoa_id]) }}" target="_blank" style="padding: 0px 3px;">
                                            <button type="button" class="btn p-0" data-toggle="tooltip" data-placement="top" data-original-title="Histórico do paciente">
                                                <span class="ti-pencil-alt"></span>
                                            </button>
                                        </a>
                                    @endcan
                                @endif

                                @if ($medico)
                                 @if (count($agendamento->atendimento) > 0)    
                                    @can('habilidade_instituicao_sessao', 'abrir_prontuario')
                                        <a href="{{ route('instituicao.pessoas.abrirProntuario', [$agendamento->pessoa]) }}" target="_blank"  style="text-decoration: none;color: unset; padding: 0px 3px;">
                                            <button type="button" class="btn p-0" aria-haspopup="true" aria-expanded="false"
                                                data-toggle="tooltip" data-placement="top" data-original-title="Atender avulso">
                                                <i class="ti-write"></i>
                                            </button>
                                        </a>
                                    @endcan
                                 @endif
                                @endif

                                @if($agendamento['obs'])
                                    <button type="button" class="btn" data-toggle="tooltip" title="" data-original-title="obs: {{$agendamento['obs']}}"  style="padding-left: 5px; padding-right: 5px; @cannot('habilidade_instituicao_sessao', 'visualizar_obs_opcionais')
                                        display: none;
                                    @endcannot">
                                        <span class="mdi mdi-comment-processing"></span>
                                    </button>
                                @endif

                                @if($agendamento->pessoa->obs)
                                    <button type="button" class="btn" data-toggle="tooltip" title="" data-original-title="obs: {{$agendamento->pessoa->obs}}"  style="padding-left: 5px; padding-right: 5px;">
                                        <span class="mdi mdi-comment-account"></span>
                                    </button>
                                @endif

                                @if($agendamento['acompanhante'] == 1)
                                    <button type="button" class="btn" data-toggle="tooltip" title="" data-original-title="Acompanhante: ({{$agendamento['acompanhante_relacao']}}) {{$agendamento['acompanhante_nome']}} - {{$agendamento['acompanhante_telefone']}}" style="padding-left: 5px; padding-right: 5px;">
                                        <span class="mdi mdi-account-multiple"></span>
                                    </button>
                                @endif

                                @if ($agendamento['status']=='agendado' || $agendamento['status']=='em_atendimento' || $agendamento->teleatendimento == 1)
                                    @if ($medico)
                                        @can('habilidade_instituicao_sessao', 'abrir_prontuario')
                                            <a class="btn noModal" href="{{ route('instituicao.agendamentos.prontuario', [$agendamento]) }}" target="_blank" data-toggle="tooltip" title="" data-original-title="Atender consultório" style="padding-left: 5px; padding-right: 5px;">
                                                <span class="mdi mdi-clipboard-text"></span>
                                            </a>
                                        @endcan
                                    @endif
                                @endif

                                @if ($agendamento->status == "finalizado_medico")
                                    @if ($medico)
                                        @can('habilidade_instituicao_sessao', 'abrir_prontuario')
                                            <a class="btn noModal" href="{{ route('instituicao.agendamentos.prontuario', [$agendamento]) }}" target="_blank" data-toggle="tooltip" title="" data-original-title="Prontuário" style="padding-left: 5px; padding-right: 5px;">
                                                <span class="mdi mdi-clipboard-text"></span>
                                            </a>
                                        @endcan
                                    @endif
                                @endif

                                @if( $agendamento['status']=='pendente' && \Carbon\Carbon::parse($agendamento['data']) == $i)
                                    <button data-id="{{$agendamento['id']}}" type="button" class="btn confirmar_agendamento" data-toggle="tooltip" title="" data-original-title="Confirmar agendamento" style="padding-left: 5px; padding-right: 5px;" >
                                        <span class="mdi mdi-check"></span>
                                    </button>
                                @endif
                                @if( $agendamento['status']=='agendado' && \Carbon\Carbon::parse($agendamento['data']) == $i)
                                    <button data-id="{{$agendamento['id']}}" type="button" class="btn finalizar_agendamento" data-toggle="tooltip" title="" data-original-title="Finalizar agendamento" style="padding-left: 5px; padding-right: 5px;" >
                                        <span class="mdi mdi-check"></span>
                                    </button>
                                @endif
                                @if( $agendamento['status']=='pendente' ||  $agendamento['status']=='agendado' || $agendamento['status']=='confirmado')
                                <button type="button" class="btn remarcar" data-id="{{$agendamento['id']}}" data-toggle="tooltip" title="" data-original-title="Remarcar horário" style="padding-left: 5px; padding-right: 5px;">
                                        <span class="mdi mdi-swap-vertical"></span>
                                    </button>
                                @endif
                                @if($agendamento['status']!='finalizado' && $agendamento['status']!='cancelado' && count($agendamento->agendamentoProcedimento) == 1 )
                                    {{-- <button data-id="{{$agendamento['id']}}" type="button" class="btn cancelar" data-toggle="tooltip" title="" data-original-title="Cancelar agendamento">
                                        <span class="mdi mdi-close-box-outline"></span>
                                    </button> --}}
                                @endif
                                @if($exige_card_aut && !$agendamento->agendamentoGuias->count() && !$agendamento->agendamentoGuias->count() && !in_array($agendamento['status'], ['cancelado','ausente'] ))
                                    <span data-toggle="tooltip" tittle="" data-original-title="Carteirinha ou autorização são obrigatórios e estão faltando" class="mdi mdi-alert icon-agenda text-danger"></span>
                                @endif
                            </div>
                        </div>
                        
                        @endif
                    </div>
                </div>
                @can('habilidade_instituicao_sessao', 'realizar_encaixe')
                @if(\Carbon\Carbon::parse($i)->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $agendaDados[$x]['duracao_atendimento'])) >= \Carbon\Carbon::now() && $agendamentos_count == 1 && $agendamento['status'] !='cancelado')
                    <div class="agenda_eventos horario_disponivel col-sm-1 p-0 m-0" data-horario="{{$i->format('H:i')}}" data-tipo="encaixe" data-agenda="{{$agendaDados[$x]['id']}}">
                        <div class="agendamento agendamento_empty">
                            <center class="agendamento_col agendamento_texto m-0 p-0" data-toggle="tooltip" title="" data-original-title="Encaixe de agenda"><button class="btn "><span class="mdi mdi-plus"></span></button></center>
                        </div>
                    </div>
                @endif
                @endcan
                @if($agendamento['status']=='cancelado' && $agendamento['usuario_id']==null && $agendamento['pessoa_id'] === null)
                    @can('habilidade_instituicao_sessao', 'cancelar_reativar_horario')
                        <div class="agendamento_col agendamento_actions col-sm-1 p-0 m-0">
                            <div class="agendamento text-center">
                                <center class="agendamento_col agendamento_texto m-0 p-0"><button type="button" data-agendamento="{{$agendamento->id}}" class="btn reativar_horario"  data-toggle="tooltip" title="" data-original-title="Reativar horário">
                                    <span class="mdi mdi-calendar"></span>
                                </button></center>
                            </div>
                        </div>
                    @endcan
                @endif
            @endif
        @endforeach


        @if(count($agendaAusente) > 0)
            @php
                $inicio = null;
                $fim = null;
                $data_i = null;
                $chave = 0;
            @endphp
            @foreach ($agendaAusente as $keyAusente => $itemAusente)
                @php
                    $inicio_verifica = strtotime($itemAusente->data." ".$itemAusente->hora_inicio);
                    $fim_verifica = strtotime($itemAusente->data." ".$itemAusente->hora_fim);
                    $data_i_verifica = $i->format("y-m-d H:i:s");
                    if(strtotime($data_i_verifica) >= $inicio_verifica && strtotime($data_i_verifica) <= $fim_verifica){
                        $inicio = strtotime($itemAusente->data." ".$itemAusente->hora_inicio);
                        $fim = strtotime($itemAusente->data." ".$itemAusente->hora_fim);
                        $data_i = $i->format("y-m-d H:i:s");
                        $chave = $keyAusente;
                    }
                @endphp
            @endforeach  
            
            @php
                if(!$data_i){
                    $inicio = strtotime($agendaAusente[0]->data." ".$agendaAusente[0]->hora_inicio);
                    $fim = strtotime($agendaAusente[0]->data." ".$agendaAusente[0]->hora_fim);
                    $data_i = $i->format("y-m-d H:i:s");
                }
            @endphp

            @if(strtotime($data_i) >= $inicio && strtotime($data_i) <= $fim)
                <div class="agenda_eventos  @if ($prestador_especialidade_id == "") horario_disponivel_nao_exibir @endif">

                    <div class="agendamento agendamento_past">
                        <div class="agendamento_col agendamento_texto">
                        Horário bloqueado para este profissional. motivo: {{ $agendaAusente[$chave]->motivo}}
                        </div>
                    </div>
                </div>
            @elseif($agendamentos_count==0 && \Carbon\Carbon::parse($i)->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $agendaDados[$x]['duracao_atendimento'])) < \Carbon\Carbon::now())
                <div class="agenda_eventos vazio_passado @if ($prestador_especialidade_id == "")
                    horario_disponivel_nao_exibir @endif">

                    <div class="agendamento agendamento_empty agendamento_past">
                        <div class="agendamento_col agendamento_texto">
                        Nenhum agendamento foi marcado para este horário
                        </div>
                    </div>
                </div>
            @elseif($agendamentos_count==0)
                {{-- <div class="agenda_eventos horario_disponivel @if ($prestador_especialidade_id == "")
                    horario_disponivel_nao_exibir
                @endif" data-horario="{{$i->format('H:i')}}" > --}}

                <div class="agenda_eventos horario_disponivel col-sm-11 p-0 m-0" data-horario="{{$i->format('H:i')}}" data-agenda="{{$agendaDados[$x]['id']}}">
                    <div class="agendamento agendamento_empty">
                        <div class="agendamento_col agendamento_texto">
                        Horário disponível
                        </div>                                                    
                    </div>
                </div>
                @can('habilidade_instituicao_sessao', 'cancelar_reativar_horario')
                <div class="agendamento_col agendamento_actions col-sm-1 p-0 m-0">
                    <div class="agendamento agendamento_empty text-center">
                        <center class="agendamento_col agendamento_texto m-0 p-0"><button type="button" data-horario="{{$i}}" data-agenda="{{$agendaDados[$x]['id']}}" class="btn cancelar_horario"  data-toggle="tooltip" title="" data-original-title="Cancelar horário">
                            <span class="mdi mdi-close-box-outline"></span>
                        </button></center>
                    </div>
                </div>
                @endcan
            @endif
                    
        @else
            @if($agendamentos_count==0 && \Carbon\Carbon::parse($i)->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $agendaDados[$x]['duracao_atendimento'])) < \Carbon\Carbon::now())
                <div class="agenda_eventos vazio_passado @if ($prestador_especialidade_id == "")
                    horario_disponivel_nao_exibir
                @endif">

                    <div class="agendamento agendamento_empty agendamento_past">
                        <div class="agendamento_col agendamento_texto">
                        Nenhum agendamento foi marcado para este horário
                        </div>
                    </div>
                </div>
            @elseif($agendamentos_count==0)
                {{-- <div class="agenda_eventos horario_disponivel @if ($prestador_especialidade_id == "")
                    horario_disponivel_nao_exibir
                @endif" data-horario="{{$i->format('H:i')}}" > --}}

                <div class="agenda_eventos horario_disponivel col-sm-11 m-0 p-0" data-horario="{{$i->format('H:i')}}" data-agenda="{{$agendaDados[$x]['id']}}">
                    <div class="agendamento agendamento_empty">
                        <div class="agendamento_col agendamento_texto">
                        Horário disponível
                        </div>
                    </div>
                </div>
                @can('habilidade_instituicao_sessao', 'cancelar_reativar_horario')
                <div class="agendamento_col agendamento_actions col-sm-1 p-0 m-0">
                    <div class="agendamento agendamento_empty text-center">
                        <center class="agendamento_col agendamento_texto m-0 p-0"><button type="button" data-horario="{{$i}}" data-agenda="{{$agendaDados[$x]['id']}}" class="btn cancelar_horario"  data-toggle="tooltip" title="" data-original-title="Cancelar horário">
                            <span class="mdi mdi-close-box-outline"></span>
                        </button></center>
                    </div>
                </div>
                @endcan
            @endif
        @endif
    </div>
</div>