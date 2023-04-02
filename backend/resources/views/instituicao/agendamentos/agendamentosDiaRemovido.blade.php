<div class="agenda_horario_row">
    <div class="agenda_horario">
        <strong>{{date('H:i', strtotime($agendamento->data))}}</strong>
    </div>
        @php unset($agendamentos_controle[$key]);$agendamentos_count++; @endphp
        {{-- {{$agendamentos_count}} --}}
        <div class="agenda_eventos agenda_eventos_row_horario @if ($agendamento['id_referente'] != null) horario_cancelado_nao_exibir @endif">

            <div class="agendamento
            @if( $agendamento['status']=='pendente' )
                status-1 clickable
            @elseif( $agendamento['status']=='agendado')
                status-2 clickable
            @elseif( $agendamento['status']=='confirmado')
                status-3 clickable
            @elseif($agendamento['status']=='cancelado')
                status-4 @if ($agendamento['pessoa_id']!=null) clickable @endif
            @elseif( $agendamento['status']=='finalizado')
                status-5 clickable
            @elseif( $agendamento['status']=='ausente')
                status-7 clickable
            @elseif( $agendamento['status']=='em_atendimento')
                status-8 clickable
            @elseif( $agendamento['status']=='finalizado_medico')
                status-9 clickable
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

                        @if ($agendamento->data_original)
                            <span data-toggle="tooltip" title="" data-original-title="A hora original do atendimento é: {{\Carbon\Carbon::parse($agendamento['data_original'])->format('H:i')}}" class="fa fa-exclamation help text-warning" ></span>
                        @endif
                        @if( $agendamento['status']=='pendente' )
                        <span data-toggle="tooltip" title="" data-original-title="Nenhuma confirmação do paciente ou clínica foi realizado" class="mdi mdi-account-alert icon-agenda"></span>

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
                        <span  style="font-weight:500;"> <i class="fa fa-phone"></i></span>
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
                        {{-- {{$agendamento}} --}}
                        @if($agendamento->agendamentoProcedimento->count() > 0)
                            @if($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo=='consulta')
                            <span data-toggle="tooltip" title="" data-original-title="Exame" style="font-weight:bold;"> <i class="mdi mdi-stethoscope"></i></span>
                            @elseif($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo=='exame')
                            <span data-toggle="tooltip" title="" data-original-title="Consulta" style="font-weight:bold;"> <i class="mdi mdi-clipboard-text"></i></span>
                            @endif
                            <span data-toggle="tooltip" title="" data-original-title="Convênio: {{$agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->convenios->nome}}" style="font-weight:bold;">
                                {{strtoupper(substr($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->convenios->nome, 0, 20))}} @if(strlen($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->convenios->nome > 20)) ... @endif
                            </span>
                            <span style="font-weight:bold;">-</span>

                            <span data-toggle="tooltip" title="" data-original-title="Procedimento: {{strtoupper($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->descricao)}}" style="font-weight:bold;">
                            {{substr(strtoupper($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->descricao), 0, 20)}} @if(strlen($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->convenios->nome > 20)) ... @endif
                            </span>
                            @if (count($agendamento->agendamentoProcedimento) > 1)
                                @php
                                    $texto = "";
                                    foreach ($agendamento->agendamentoProcedimento as $key => $value) {
                                        $texto .=  $value->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->descricao.", ";
                                    }
                                @endphp

                                <span class="" style="font-weight:bold;" data-html="true" data-toggle="tooltip" data-placement="right" title="" data-original-title="{{substr($texto, 0, -2)}}">(+{{count($agendamento->agendamentoProcedimento)-1}} Exame)</span>
                            @endif
                        @endif
                    </div>
                    <div class="agendamento_col agendamento_actions">
                        @if($agendamento->status == "agendado")
                            <span data-toggle="tooltip" data-placement="top" data-original-title="Tempo de espera do paciente"><small>{{APP\Agendamentos::timeEspera($agendamento->atendimento[0]->data_hora)}}</small></span>
                        @endif
                        <div class="btn-group">
                            @if(count($this->usuario_prestador) > 0 && $this->usuario_prestador[0]->tipo == 2 && $agendamento->status != 'em_atendimento')
                                @can('habilidade_instituicao_sessao', 'abrir_prontuario')
                                    <a href="{{ route('instituicao.pessoas.abrirProntuarioResumo', [$agendamento->pessoa_id]) }}" target="_blank" style="padding: 0px 3px;">
                                        <button type="button" data-toggle="tooltip" data-placement="top" data-original-title="Histórico do paciente">
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
                                <button type="button" class="btn" data-toggle="tooltip" title="" data-original-title="obs: {{$agendamento['obs']}}" style="padding-left: 5px; padding-right: 5px; @cannot('habilidade_instituicao_sessao', 'visualizar_obs_opcionais')
                                    display: none;
                                @endcannot">
                                    <span class="mdi mdi-comment-processing"></span>
                                </button>
                            @endif

                            @if($agendamento->pessoa->obs)
                                <button type="button" class="btn" data-toggle="tooltip" title="" data-original-title="obs: {{$agendamento->pessoa->obs}}" style="padding-left: 5px; padding-right: 5px;">
                                    <span class="mdi mdi-comment-account"></span>
                                </button>
                            @endif

                            @if($agendamento['acompanhante'] == 1)
                                <button type="button" class="btn" data-toggle="tooltip" title="" data-original-title="Acompanhante: ({{$agendamento['acompanhante_relacao']}}) {{$agendamento['acompanhante_nome']}} - {{$agendamento['acompanhante_telefone']}}" style="padding-left: 5px; padding-right: 5px;">
                                    <span class="mdi mdi-account-multiple"></span>
                                </button>
                            @endif

                            @if( $agendamento['status']=='pendente' ||  $agendamento['status']=='agendado' || $agendamento['status']=='confirmado')
                                <button data-id="{{$agendamento['id']}}" type="button" class="btn remarcar" data-toggle="tooltip" title="" data-original-title="Remarcar horário" style="padding-left: 5px; padding-right: 5px;">
                                    <span class="mdi mdi-swap-vertical"></span>
                                </button>
                            @endif
                            {{-- @if($agendamento['status']!='finalizado' && $agendamento['status']!='cancelado' && count($agendamento->agendamentoProcedimento) == 1) --}}
                            @if($agendamento['status']!='finalizado' && $agendamento['status']!='cancelado')
                                {{-- <button data-id="{{$agendamento['id']}}" type="button" class="btn cancelar"  data-toggle="tooltip" title="" data-original-title="Cancelar agendamento">
                                    <span class="mdi mdi-close-box-outline"></span>
                                </button> --}}
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
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
</div>