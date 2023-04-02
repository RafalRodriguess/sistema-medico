<div>
<form action="javascript:void(0)" >
    <div class="row">
        <div class="col-md-10">
            <div class="form-group" style="margin-bottom: 0px !important;">
                {{-- <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" value="{{($pesquisa) ? $pesquisa : ''}}" onblur="callChangePesquisaRegistro()" class="form-control" placeholder="Pesquise pelo nome do paciente ou cpf..."> --}}
                <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" value="{{($pesquisa) ? $pesquisa : ''}}"  class="form-control"  placeholder="Pesquise pelo nome do paciente ou cpf...">
            </div>
        </div>
    </div>
</form>

<hr>
<table wire:loading.remove  class="tablesaw table-bordered table">
        <thead>    
            <tr>
                <th></th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="4">Num. Registro</th>
                {{-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Tipo</th> --}}
                {{-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Setor</th> --}}
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Situação</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="2">Data</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="1">Paciente</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="persist">Profissional</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="1">Procedimento(s)</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="1">Forma de pagamento</th>
                
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ( $agendamentos_geral as $agendamento )
                <tr class="agendamento-registro 
                    @if( $agendamento->status=='pendente' )
                        status-1
                    @elseif( $agendamento->status=='agendado')
                        status-2
                    @elseif( $agendamento->status=='confirmado')
                        status-3
                    @elseif($agendamento->status=='cancelado')
                        status-4
                    @elseif( $agendamento->status=='finalizado')
                        status-5
                    @elseif( $agendamento->status=='ausente')
                        status-7
                    @elseif( $agendamento->status=='em_atendimento')
                        status-8
                    @elseif( $agendamento->status=='finalizado_medico')
                        status-9
                    @endif"
                >
                
          
                    <td class="">
                        <span class="
                            @if($agendamento->status=='pendente' )
                                mdi mdi-account-alert
                            @elseif($agendamento->status=='agendado')
                                mdi mdi-account-convert
                            @elseif($agendamento->status=='confirmado')
                                mdi mdi-account-check
                            @elseif($agendamento->status=='cancelado' )
                                far fa-frown
                            @elseif($agendamento->status=='finalizado')
                                mdi mdi-checkbox-marked-circle-outline
                            @elseif($agendamento->status=='ausente')
                                mdi mdi-account-remove
                            @elseif($agendamento->status=='em_atendimento')
                                mdi mdi-account-convert
                            @elseif($agendamento->status=='finalizado_medico')
                                mdi mdi-checkbox-marked-circle-outline
                            @endif
                        "></span>
                    </td>    
                    <td class="title">{{ $agendamento->id }}</td>

                    @if($agendamento->instituicoesAgenda->prestadores)

                        {{-- @if(sizeof($agendamento->agendamentoProcedimento) > 0)
                            {{-- @if($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo=='consulta')
                                <td>Consulta</td>
                            @elseif($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo=='exame')
                                <td>Exame</td>
                            @endif --}}
                            {{-- <td>@php if(!empty($agendamento->setor)) echo $agendamento->setor->descricao @endphp</td>


                            @else
                            <td>n definido</td>
                        @endif --}}

                        <td>
                            @if($agendamento->status=='em_atendimento') Em consultório
                            @elseif($agendamento->status == "finalizado_medico") Finalizado consultório
                            @else {{ ucfirst($agendamento->status) }} @endif
                        
                        </td>
                        
                        <td>{{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y H:i') }}</td>
                        <td>
                            @if(!empty($agendamento->pessoa))
                                {{ ucfirst($agendamento->pessoa->nome) }}  {{(!empty($agendamento->pessoa->telefone1)) ? " - ". $agendamento->pessoa->telefone1 : ""}}
                            @endif
                        </td>
                        <td>{{ ucfirst($agendamento->instituicoesAgenda->prestadores->prestador->nome) }}</td>

                        @if(sizeof($agendamento->agendamentoProcedimento) > 0)

                            <td>
                                @foreach ($agendamento->agendamentoProcedimento as $procedimentos)
                                    @if (!empty($procedimentos->procedimentoInstituicaoConvenio->procedimentoInstituicao) > 0)
                                    {{strtoupper($procedimentos->procedimentoInstituicaoConvenio->convenios->nome)}} - {{ucfirst($procedimentos->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->descricao)}}; 
                                    @endif
                                @endforeach
                            </td>

                        @else
                            <td>n definido</td>
                        @endif

                    @elseif($agendamento->instituicoesAgenda->procedimentos)
                        {{-- @if($agendamento->instituicoesAgenda->procedimentos->procedimento->tipo=='consulta')
                            <td>Consulta</td>
                        @elseif($agendamento->instituicoesAgenda->procedimentos->procedimento->tipo=='exame')
                            <td>Exame</td>
                        @endif --}}
                        {{-- <td>@php if(!empty($agendamento->setor)) echo $agendamento->setor @endphp</td> --}}
                        <td>{{ ucfirst($agendamento->status) }}</td>
                        <td>{{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y H:i') }}</td>
                        <td></td>
                        <td>{{ ucfirst($agendamento->instituicoesAgenda->procedimentos->procedimento->descricao) }}</td>
                    @endif

                    <td>
                        @if (count($agendamento->contaReceber) > 0)   
                            {{ ($agendamento->contaReceber[0]->forma_pagamento) ? App\ContaReceber::forma_pagamento_texto($agendamento->contaReceber[0]->forma_pagamento) : '-'  }}
                        @endif
                    </td>
                    <td style="width: 120px;">
                        {{-- <div class="col-md-2"> --}}
                            {{-- <div class="form-group" style="margin-bottom: 0px !important;"> --}}
                                @if($agendamento->instituicoesAgenda->prestadores)
                                    <a href="{{ route('instituicao.agendamentos.index', array('prestador_especialidade_id'=>$agendamento->instituicoesAgenda->prestadores->id,'data'=>\Carbon\Carbon::parse($agendamento->data)->format('d/m/Y'))) }}">
                                        <button type="button" class="btn btn-xs btn-info" title='Ver na agenda' data-toggle="tooltip" data-placement="top" data-original-title="Ver na agenda"><i class="ti-calendar"></i></button>
                                    </a>
                                @elseif($agendamento->instituicoesAgenda->procedimentos)
                                    <a href="{{ route('instituicao.agendamentos.index', array('procedimento_instituicao_id'=>$agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->id,'data'=>\Carbon\Carbon::parse($agendamento->data)->format('d/m/Y'))) }}">
                                        <button type="button" class="btn btn-xs btn-info" title='Ver na agenda' data-toggle="tooltip" data-placement="top" data-original-title="Ver na agenda"><i class="ti-calendar"></i></button>
                                    </a>
                                @endif
                                @if ($medico == true)
                                    @can('habilidade_instituicao_sessao', 'abrir_prontuario')
                                        <a href="{{ route('instituicao.agendamentos.prontuario', [$agendamento]) }}" target="_blank">
                                            <button type="button" class="btn btn-xs btn-info" aria-haspopup="true" aria-expanded="false"
                                                data-toggle="tooltip" data-placement="top" data-original-title="Histórico do paciente">
                                                <i class="ti-pencil-alt"></i>
                                            </button>
                                        </a>
                                    @endcan
                                @endif
                                @can('habilidade_instituicao_sessao', 'editar_pessoas')
                                    <a href="{{route('instituicao.pessoas.edit', [$agendamento->pessoa])}}" target="_blank">
                                        <button type="button" class="btn btn-xs btn-info" data-toggle="tooltip" data-placement="top" data-original-title="Ficha do paciente"><i class="mdi mdi-account-card-details"></i></button>
                                    </a>
                                @endcan
                            {{-- </div> --}}
                        {{-- </div> --}}
                    </td>
                </tr>
            @endforeach
        </tbody>
</table>
<div wire:loading style="width:100%">
    <div class="container" align="center">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                Carregando...
                <br>
                <img width="70" src="{{ asset('material/assets/images/spinner.gif') }}" alt="">
            </div>
        </div>
    </div>
</div>

<div style="float: right">
    {{ $agendamentos_geral->links() }}
</div>

</div>

<script>
    $(document).ready(function(){
        window.livewire.rescan()
    })
    $("#pesquisa").keypress(function(event) {
        if (event.which == 13) {
            console.log("aqui");
            
            event.preventDefault();
            callChangePesquisaRegistro();
        }
    });
</script>

