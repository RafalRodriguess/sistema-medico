@php
    $procedimentos_array = [];
    $pacientes = [];
@endphp
<table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" style="margin: 0px">
    <thead>
        <tr>
            <th >Cod Agenda</th>
            <th >Paciente</th>
            <th >Data</th>
            <th >Convênio</th>
            <th >Serviço / Procedimento</th>
            <th>Valor procedimento</th>
            <th>Valor convênio</th>
        </tr>
    </thead>
    <tbody>
        @php
            // dd($agendamentos[0]);
            
            $total_atual = 0;
            $total_convenio = 0;
        @endphp
        @foreach($agendamentos as $item)
            @php
                $procedimentos = 0;
            @endphp
            @foreach ($item->agendamentoProcedimento as $procedimento)    
                <tr >
                    @php
                        $total_atual += $procedimento->valor_atual;
                        $total_convenio += $procedimento->valor_convenio;
                    @endphp

                    <td>{{$item->id}}</td>
                    <td >{{$item->pessoa->nome}}</td>
                    <td >{{date('d/m/Y H:i', strtotime($item->data))}}</td>    
                
                    <td >{{$procedimento->procedimentoInstituicaoConvenioTrashed->convenios->nome}}</td>
                    <td >
                        {{$procedimento->procedimentoInstituicaoConvenioTrashed->procedimentoInstituicao->procedimento->descricao}}
                    </td>
                    
                    <td>                            
                       {{number_format($procedimento->valor_atual, 2, ',', '.')}}
                    </td>

                    <td>
                        {{number_format($procedimento->valor_convenio, 2, ',', '.')}}
                        
                    </td>                        
                </tr>   
                @php
                    $procedimentos += $procedimento->valor_atual;

                    $proc_nome = $procedimento->procedimentoInstituicaoConvenioTrashed->procedimentoInstituicao->procedimento->descricao;
                    $convenio_nome = $procedimento->procedimentoInstituicaoConvenioTrashed->convenios->nome;
                    
                    if(!empty($procedimentos_array[$convenio_nome])){
                        $procedimentos_array[$convenio_nome] = array(
                            'proc' => $proc_nome,
                            "valor" => $procedimentos_array[$convenio_nome]["valor"] + $procedimento->valor_atual,
                            "qtd" => $procedimentos_array[$convenio_nome]["qtd"] + 1  
                        );
                    }else{
                        $procedimentos_array[$convenio_nome] = array(
                            'proc' => $proc_nome,
                            "valor" => $procedimento->valor_atual,
                            "qtd" => 1  
                        );
                    }
                @endphp                     
            @endforeach                
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td >
                <b>Total Repasse</b>
            </td>
            <td colspan="4">
                
            </td>
           
            <td>
                <b>{{number_format($total_atual, 2, ',', '.')}}</b>
            </td>
            <td>
                <b>{{number_format($total_convenio, 2, ',', '.')}}</b>
            </td>
        </tr>
        
    </tfoot>
</table>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Convênio</th>
            <th>Procedimento</th>
            <th>Quantidade</th>
            <th>Valor</th>
        </tr>
    </thead>

    <tbody>
        @foreach($procedimentos_array as $convenio => $valor)
            <tr>
                <td>{{$convenio}}</td>
                <td>{{$valor['proc']}}</td>
                <td class="text-center">{{$valor['qtd']}}</td>
                <td class="text-right">R$ {{number_format($valor['valor'], 2, ',', '.')}}</td>
            </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th></th>
            <th>Total</th>
            <th class="text-center">{{array_sum(array_column($procedimentos_array, "qtd"))}}</th>
            <th class="text-right">R$ {{number_format(array_sum(array_column($procedimentos_array, "valor")), 2, ',', '.')}}</th>
        </tr>
        {{-- <tr><th colspan="2"><small>{{count($pacientes)}} pacientes atendidos</small></th></tr> --}}
    </tfoot>
</table>