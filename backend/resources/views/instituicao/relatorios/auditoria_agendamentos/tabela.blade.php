<style>
    .status{
        background-color: #d1dade;
        color: #5e5e5e;
        font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 10px;
        font-weight: 600;
        padding: 3px 8px;
        text-shadow: none;
        border-radius: 5px;
    }
    .cor-0 {
    background-color:#cc0404;
    border-color:#cc0404;
    color: white;
    }

    .cor-1 {
    background-color: #26c6da;
    border-color: #26c6da;
    color: #1b5c64;
    }

    .cor-2    {
    background-color: #ffcf8e;
    border-color: #ffcf8e;
    color: #81653f;
    }

    .cor-3 {
    background-color: #009688;
    border-color: #009688;
    color: #fff;
    }

    .cor-4 {
    background-color: #78909C;
    border-color: #78909C;
    color: #fff;
    }

    .cor-5 {
    background-color: #745af2;
    border-color: #745af2;
    color: #fff;
    }

    .cor-6 {
    background-color: #ffcf8e;
    border-color: #ffcf8e;
    color: #877052;
    }

    .cor-7 {
    background-color: #899093;
    border-color: #899093;
    color: #fff;
    }
    .cor-8 {
    background-color: #8eff9e;
    border-color: #8eff9e;
    color: #285a2f;
    }
    .cor-9 {
    background-color: #63dbae;
    border-color: #63dbae;
    color: #4db890;
    }
</style>
<table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" style="margin: 0px">
    <thead>
        <tr>
            <th >Data Agendamento</th>
            <th >Data Auditoria</th>
            <th >Status</th>
            <th >Usuario</th>
            <th >Paciente</th>            
            <th >Prestador</th>
            <th >Procedimentos</th>
        </tr>
    </thead>
    <tbody>
        @foreach($auditorias as $item)
           <tr>
                <td>
                    @if (!empty($item->agendamentos) > 0)    
                        {{date('d/m/Y', strtotime($item->agendamentos[0]->data))}}
                    @endif
                </td>
                <td>{{date('d/m/Y', strtotime($item->data))}}</td>
                <td>
                    <span class="status @if( $item->status=='pendente' )
                        cor-1
                    @elseif( $item->status=='agendado')
                        cor-2
                    @elseif( $item->status=='confirmado')
                        cor-3
                    @elseif($item->status=='cancelado')
                        cor-4
                    @elseif( $item->status=='finalizado')
                        cor-5
                    @elseif( $item->status=='ausente')
                        cor-7
                    @elseif( $item->status=='em_atendimento')
                        cor-8
                    @elseif( $item->status=='finalizado_medico')
                        cor-9
                    @endif">{{App\Agendamentos::status_para_texto($item->status)}}</span>
                </td>
                <td>
                    @if ($item->log == "AGENDAMENTO_AUSENTE_AUTOMATICO")
                        Agendamento ausente automatico pelo sistema
                    @else
                        @if (!empty($item->usuarios))
                            {{$item->usuarios->nome}}
                        @endif
                    @endif
                </td>
                @if (!empty($item->agendamentos))    
                    <td>{{$item->agendamentos[0]->instituicoesAgenda->prestadores->prestador->nome}}</td>
                    <td>{{$item->agendamentos[0]->pessoa->nome}}</td>
                    <td>
                        @if ($item->agendamentos[0]->agendamentoProcedimento)
                            @foreach ($item->agendamentos[0]->agendamentoProcedimento as $key => $procedimentoItem)
                                @if ($procedimentoItem->procedimentoInstituicaoConvenio)
                                    @if ($procedimentoItem->procedimentoInstituicaoConvenio->procedimentoInstituicao)
                                        @if ($procedimentoItem->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento)
                                            @if ($key == 1)
                                                {{$procedimentoItem->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->descricao}}
                                            @else
                                                <br>{{$procedimentoItem->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->descricao}}
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </td>
                @else
                    <td></td>
                    <td></td>
                    <td></td>
                @endif
           </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align: right"><b>Total Registros</b></td>
            {{-- <td>R$ {{number_format($total, 2, ',','.')}}</td> --}}
            <td>{{count($auditorias)}}</td>
        </tr>
    </tfoot>
</table>