<table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" >
    <thead>
        <tr>
            {{-- <th data-breakpoints="xs" hidden></th> --}}
            <th >Tipo</th>
            <th >Data Vencimento</th>
            <th >Data pago/recebido</th>
            <th >Data compensação</th>
            <th >Conta caixa</th>
            <th >Fornecedor/Paciente</th>       
            <th >
                Valor Parcela
            </th>
            <th >
                Valor Pago/Recebido
            </th>
            <th >Obs</th>
            {{-- <th data-breakpoints="all">Tipo</th> --}}
        </tr>
    </thead>
    <tbody>
        @php
            $data = null;
            // $saldo_anterior = 0;
            $novo = 0;
        @endphp
        {{-- @foreach($union as $item) --}}
        @for ($i = 0; $i < count($union); $i++)
            @php
                if($dados['tipo_pesquisa'] == "bancaria"){
                    if(empty($union[$i]->data_compensacao)){
                        $data = $union[$i]->data_pago;
                    }else{
                        $data = $union[$i]->data_compensacao;
                    }
                }else{
                    $data = $union[$i]->data_vencimento;
                }
            @endphp
            @if ($i == 0)
                <tr>
                    <td colspan="9" style="text-align: right">
                        Saldo inicial = <span @if ($saldo_anterior < 0)
                                style="color: red"
                            @else
                                style="color: green"
                        @endif >{{number_format($saldo_anterior, 2, ',','.')}}</span>
                    </td>
                    <td hidden></td>
                    <td hidden></td>
                    <td hidden></td>
                    <td hidden></td>
                    <td hidden></td>
                    <td hidden></td>
                </tr>
            @endif
            <tr>
                {{-- <td hidden></td> --}}
                <td>
                    @if ($union[$i]->natureza == 'conta_receber')
                    <i class="mdi mdi-arrow-right-bold" style="color: green"></i>
                    @else
                    <i class="mdi mdi-arrow-left-bold" style="color: red"></i>
                    @endif
                </td>
                <td>
                    @if ($union[$i]->natureza == 'conta_pagar')
                        {{\Carbon\Carbon::createFromFormat('Y-m-d',$union[$i]->data_vencimento)->format('d/m/Y')}}                    
                    @endif
                    @if ($union[$i]->natureza == 'conta_receber')
                        {{\Carbon\Carbon::createFromFormat('Y-m-d',$union[$i]->data_vencimento)->format('d/m/Y')}}                    
                    @endif  
                </td>
                <td>
                    @if ($union[$i]->data_pago)
                        @if ($union[$i]->natureza == 'conta_pagar')
                            {{\Carbon\Carbon::createFromFormat('Y-m-d',$union[$i]->data_pago)->format('d/m/Y')}}                    
                        @endif
                        @if ($union[$i]->natureza == 'conta_receber')
                            {{\Carbon\Carbon::createFromFormat('Y-m-d',$union[$i]->data_pago)->format('d/m/Y')}}                    
                        @endif  
                    @endif
                </td>
                <td>
                    @if ($union[$i]->data_compensacao)
                        @if ($union[$i]->natureza == 'conta_pagar')
                            {{\Carbon\Carbon::createFromFormat('Y-m-d',$union[$i]->data_compensacao)->format('d/m/Y')}}                    
                        @endif
                        @if ($union[$i]->natureza == 'conta_receber')
                            {{\Carbon\Carbon::createFromFormat('Y-m-d',$union[$i]->data_compensacao)->format('d/m/Y')}}                    
                        @endif  
                    @endif
                </td>

                <td>{{($union[$i]->contaCaixa) ? $union[$i]->contaCaixa->descricao : '-'}}</td>

                <td>
                    @if ($union[$i]->natureza == 'conta_pagar')
                        @if ($union[$i]->prestador)
                            Fornecedor: {{$union[$i]->prestador->nome}};                            
                        @elseif ($union[$i]->paciente)
                            Paciente: {{$union[$i]->paciente->nome}};
                        @endif                
          
                    @endif

                    @if ($union[$i]->natureza == 'conta_receber')
                        @if ($union[$i]->paciente)
                            Paciente: {{$union[$i]->paciente->nome}};
                        @elseif(!$union[$i]->pessoa_id)
                            Paciente avulso!
                        @endif                 
                    @endif  
                </td>
                <td>
                    R$ {{number_format($union[$i]->valor_parcela, 2, ',','.')}} 
                </td>
                <td>
                    R$ {{number_format($union[$i]->valor, 2, ',','.')}} 
                </td>
                <td>
                    {{$union[$i]->obs}}
                </td>
            </tr>
            {{-- @if (($i+1) < count($union)) --}}
                {{-- @if ($data == $union[$i+1]->data_vencimento) --}}
                    @if ($union[$i]->natureza == 'conta_pagar')
                        @php
                            $saldo_anterior = $saldo_anterior - $union[$i]->valor;
                        @endphp               
                    @else 
                        @php
                            $saldo_anterior = $saldo_anterior + $union[$i]->valor;
                        @endphp
                    @endif  
                {{-- @endif --}}
            {{-- @endif --}}
            @if (($i+1) < count($union))

                @if ($dados['tipo_pesquisa'] == "bancaria")
                    @if (empty($union[$i+1]->data_compensacao))
                        @if ($data != $union[$i+1]->data_pago)
                            @php
                                $novo = 1;
                            @endphp
                        @endif
                    @else
                        @if ($data != $union[$i+1]->data_compensacao)
                            @php
                                $novo = 1;
                            @endphp
                        @endif
                    @endif

                @else

                    @if ($data != $union[$i+1]->data_vencimento)
                        @php
                            $novo = 1;
                        @endphp
                    @endif
                @endif
            @endif

            @if (($i+1) < count($union))
                @if ($novo == 1)
                    <tr>
                        <td colspan="9" style="text-align: right">
                            Saldo final do dia = <span @if ($saldo_anterior < 0)
                                    style="color: red"
                                @else
                                    style="color: green"
                            @endif>{{number_format($saldo_anterior, 2, ',','.')}}</span>
                        </td>
                        <td hidden></td>
                        <td hidden></td>
                        <td hidden></td>
                        <td hidden></td>
                        <td hidden></td>
                        <td hidden></td>
                    </tr>
                @endif
            @else
                <tr>
                    <td colspan="9" style="text-align: right">
                        Saldo final do dia = <span @if ($saldo_anterior < 0)
                                style="color: red"
                            @else
                                style="color: green"
                        @endif>{{number_format($saldo_anterior, 2, ',','.')}}</span>
                    </td>
                    <td hidden></td>
                    <td hidden></td>
                    <td hidden></td>
                    <td hidden></td>
                    <td hidden></td>
                    <td hidden></td>
                </tr> 
            @endif

            

            @if ($novo == 1)
                <tr>
                    <td colspan="9"></td>
                    <td hidden></td>
                    <td hidden></td>
                    <td hidden></td>
                    <td hidden></td>
                    <td hidden></td>
                    <td hidden></td>
                </tr>
                <tr>
                    <td colspan="9" style="text-align: right">
                        Saldo inicial = <span @if ($saldo_anterior < 0)
                                style="color: red"
                            @else
                                style="color: green"
                        @endif>{{number_format($saldo_anterior, 2, ',','.')}}</span>
                    </td>
                    <td hidden></td>
                    <td hidden></td>
                    <td hidden></td>
                    <td hidden></td>
                    <td hidden></td>
                    <td hidden></td>
                </tr>
                @php
                    $novo = 0;
                @endphp
            @endif
        @endfor
        {{-- @endforeach --}}
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">
                Total a receber: R$ {{number_format($a_receber, 2, ',', '.')}}
            </td>
            <td colspan="3">
                Total a pagar: R$ {{number_format($a_pagar, 2, ',', '.')}}
            </td>
            <td colspan="3">
                Total: R$ {{number_format($total_previsto, 2, ',', '.')}}
            </td>
        </tr>
        <tr>
            <td colspan="3">
                Total recebidos: R$ {{number_format($recebidos, 2, ',', '.')}}
            </td>
            <td colspan="3">
                Total pagos: R$ {{number_format($pagos, 2, ',', '.')}}
            </td>
            <td colspan="3">
                Total: R$ {{number_format($total, 2, ',', '.')}}
            </td>
        </tr> 
    </tfoot>
</table>