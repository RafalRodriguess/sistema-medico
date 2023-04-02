<table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" >
    <thead>
        <tr>
            {{-- <th data-breakpoints="xs" hidden></th> --}}
            <th >Tipo</th>
            <th >Data Vencimento</th>
            <th >Data pago/recebido</th>
            <th >Data compensação</th>       
            <th >Conta Caixa</th>      
            <th >Fornecedor/Paciente</th>       
            <th >Valor Parcela            </th>
            <th >Valor Pago/Recebido            </th>
            <th >Obs</th>
            {{-- <th data-breakpoints="all">Tipo</th> --}}
        </tr>
    </thead>
    <tbody>
        @foreach($union as $item)
            <tr>
                {{-- <td hidden></td> --}}
                <td>
                    @if ($item->natureza == 'conta_receber')
                    <i class="mdi mdi-arrow-right-bold" style="color: green"></i>
                    @else
                    <i class="mdi mdi-arrow-left-bold" style="color: red"></i>
                    @endif
                </td>
                <td>
                    @if ($item->natureza == 'conta_pagar')
                        {{\Carbon\Carbon::createFromFormat('Y-m-d',$item->data_vencimento)->format('d/m/Y')}}                    
                    @endif
                    @if ($item->natureza == 'conta_receber')
                        {{\Carbon\Carbon::createFromFormat('Y-m-d',$item->data_vencimento)->format('d/m/Y')}}                    
                    @endif  
                </td>
                <td>
                    @if ($item->data_pago)
                        @if ($item->natureza == 'conta_pagar')
                            {{\Carbon\Carbon::createFromFormat('Y-m-d',$item->data_pago)->format('d/m/Y')}}                    
                        @endif
                        @if ($item->natureza == 'conta_receber')
                            {{\Carbon\Carbon::createFromFormat('Y-m-d',$item->data_pago)->format('d/m/Y')}}                    
                        @endif  
                    @endif
                </td>
                <td>
                    @if ($item->data_compensacao)
                        @if ($item->natureza == 'conta_pagar')
                            {{\Carbon\Carbon::createFromFormat('Y-m-d',$item->data_compensacao)->format('d/m/Y')}}                    
                        @endif
                        @if ($item->natureza == 'conta_receber')
                            {{\Carbon\Carbon::createFromFormat('Y-m-d',$item->data_compensacao)->format('d/m/Y')}}                    
                        @endif  
                    @endif
                </td>

                <td>{{(!empty($item->contaCaixa)) ? $item->contaCaixa->descricao : "-"}}</td>

                <td>
                    @if ($item->natureza == 'conta_pagar')
                        @if ($item->prestador)
                            Fornecedor: {{$item->prestador->nome}};                            
                        @elseif ($item->paciente)
                            Paciente: {{$item->paciente->nome}};
                        @endif                
                    @endif
                    @if ($item->natureza == 'conta_receber')
                        @if ($item->paciente)
                            Paciente: {{$item->paciente->nome}};
                        @elseif(!$item->pessoa_id)
                            Paciente avulso!
                        @endif            
                    @endif  
                </td>
                <td>
                    R$ {{number_format($item->valor_parcela, 2, ',','.')}} 
                </td>
                <td>
                    R$ {{number_format($item->valor, 2, ',','.')}} 
                </td>
                <td>
                    {{$item->obs}}
                </td>
            </tr>
        @endforeach
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