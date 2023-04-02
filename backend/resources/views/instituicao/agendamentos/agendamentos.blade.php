    <h5 class="text-center">
        @if($usuarioAgendamentos->count() == 0)
        Nenhum agendamento registrado
        @elseif($usuarioAgendamentos->count() == 1 )
        1 agendamento registrado
        @else
        {{$usuarioAgendamentos->count()}} agendamentos registrados
        @endif
    </h5>
  
    <div class="list-group white-bg scrollable">
        @foreach ( $usuarioAgendamentos as $uagendamento)
            <div class="list-group-item">
                <h5>{{\Carbon\Carbon::parse($uagendamento['data'])->format('d/m/Y H:i')}}</h5>
                <div class="small">
                        @if($uagendamento->instituicoesAgenda->prestadores)
                            <p title="{{($uagendamento->instituicoesAgenda->prestadores->especialidade) ? ucwords($uagendamento->instituicoesAgenda->prestadores->especialidade->nome) : ""}} - {{ucwords($uagendamento->instituicoesAgenda->prestadores->prestador->nome)}}" class="noWrap">{{($uagendamento->instituicoesAgenda->prestadores->especialidade) ? ucwords($uagendamento->instituicoesAgenda->prestadores->especialidade->nome) : ""}} - {{ucwords($uagendamento->instituicoesAgenda->prestadores->prestador->nome)}}</p>
                        @endif
                        <p>

                        <button class="btn @if( $uagendamento['status']=='pendente' )
                            agendamento status-1"> <span class="mdi mdi-account-alert icon-agenda"></span>
                        @elseif( $uagendamento['status']=='agendado')
                            agendamento status-2"> <span class="mdi mdi-account-convert icon-agenda"></span>
                        @elseif( $uagendamento['status']=='confirmado')
                            agendamento status-3"> <span class="mdi mdi-account-convert icon-agenda"></span>
                        @elseif($uagendamento['status']=='cancelado' )
                            agendamento status-4"> <span class="far fa-frown"></span>
                        @elseif( $uagendamento['status']=='finalizado')
                            agendamento status-5"> <span class="mdi mdi-checkbox-marked-circle-outline icon-agenda"></span>
                        @elseif( $uagendamento['status']=='ausente')
                            agendamento status-7"> <span class="mdi mdi-account-remove icon-agenda"></span>
                        @elseif( $uagendamento['status']=='em_atendimento')
                            agendamento status-8"> <span class="ti-id-badge"></span>
                            @php $uagendamento['status'] = "em consultório" @endphp
                        @elseif( $uagendamento['status']=='finalizado_medico')
                            agendamento status-9"> <span class="mdi mdi-checkbox-marked-circle-outline icon-agenda"></span>
                            @php $uagendamento['status'] = "finalizado consultório" @endphp
                        @elseif( $uagendamento['status']=='desistencia')
                            agendamento status-10"> <span class="mdi icon-agenda"></span>
                        @elseif( $uagendamento['status']=='excluir')
                            agendamento border"> <span class="mdi mdi-trash-can icon-agenda"></span>
                            @php $uagendamento['status'] = "Excluido" @endphp
                        @else
                            ">
                        @endif
                        {{ucwords($uagendamento['status'])}}</button>
                        </p>
                        @foreach ( $uagendamento->agendamentoProcedimentoTashed as $agendamentoProcedimento) 
                            <p><span class="far fa-hospital"></span> {{strtoupper($agendamentoProcedimento->procedimentoInstituicaoConvenioTrashed->convenios->nome)}} - {{strtoupper  ($agendamentoProcedimento->procedimentoInstituicaoConvenioTrashed->procedimentoInstituicaoExcluidos->procedimentoTrashed->descricao)}}</p>
                        @endforeach

                    </div>
            </div>
        @endforeach
    </div>