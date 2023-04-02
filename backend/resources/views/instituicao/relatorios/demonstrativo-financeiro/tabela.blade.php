<table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" >
    <thead>
        <tr>
            {{-- <th data-breakpoints="xs" hidden></th> --}}
            <th >Tipo</th>
            <th >Fornecedor / Paciente</th>            
            <th >Natureza</th>
            <th >Plano de conta</th>
            <th >Descrição</th>
            <th >Conta / Caixa</th>
            <th data-breakpoints="all">Data vencimento</th>            
            <th data-breakpoints="all">Valor parcela</th>
            <th data-breakpoints="all">Data pago</th>
            <th data-breakpoints="all">Data compensação</th>
            <th >Valor Pago/Recebido</th>
            <th data-breakpoints="all">Forma pagamento</th>
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
                        @if ($item->prestador)
                            Fornecedor: {{$item->prestador->nome}};                            
                        @elseif ($item->paciente)
                            Paciente: {{$item->paciente->nome}};
                        @elseif($item->fornecedor)
                            Fornecedor: {{(!empty($conta_pagar->fornecedor['nome_fantasia'])) ? $conta_pagar->fornecedor['nome_fantasia'] : $conta_pagar->fornecedor['nome']}}
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
                    {{ucwords(str_replace("_", " ", $item->natureza))}}
                </td>

                <td>
                    @if ($item->natureza == 'conta_pagar')
                        {{$item->planoConta ? $item->planoConta->descricao : '-'}}                    
                    @endif
                    @if ($item->natureza == 'conta_receber')
                        {{$item->planoConta ? $item->planoConta->descricao : '-'}}                    
                    @endif
                </td>

                <td>{{$item->descricao}}</td>

                <td>{{(!empty($item->contaCaixa)) ? $item->contaCaixa->descricao : "-"}}</td>
                
                <td>
                    @if ($item->natureza == 'conta_pagar')
                        {{\Carbon\Carbon::createFromFormat('Y-m-d',$item->data_vencimento)->format('d/m/Y')}}                    
                    @endif
                    @if ($item->natureza == 'conta_receber')
                        {{\Carbon\Carbon::createFromFormat('Y-m-d',$item->data_vencimento)->format('d/m/Y')}}                    
                    @endif                                 
                </td>
                <td>               
                    R$ {{number_format($item->valor_parcela, 2, ',','.')}}
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
                    @if ($item->natureza == 'conta_pagar')
                        {{$item->data_compensacao ? \Carbon\Carbon::createFromFormat('Y-m-d',$item->data_compensacao)->format('d/m/Y') : '-'}}                    
                    @endif
                    @if ($item->natureza == 'conta_receber')
                        {{$item->data_compensacao ? \Carbon\Carbon::createFromFormat('Y-m-d',$item->data_compensacao)->format('d/m/Y') : '-'}}                    
                    @endif    
                </td>
                <td>
                    @if ($item->status == 1)
                        R$ {{number_format($item->valor, 2, ',','.')}} 
                    @else
                        -
                    @endif
                </td>
                <td>                   
                        {{($item->forma_pagamento) ? App\ContaPagar::forma_pagamento_texto($item->forma_pagamento) : '-'}}
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4">
                Total a receber: R$ {{number_format($a_receber, 2, ',', '.')}}
            </td>
            <td colspan="4">
                Total a pagar: R$ {{number_format($a_pagar, 2, ',', '.')}}
            </td>
            <td colspan="4">
                Total: R$ {{number_format($total_previsto, 2, ',', '.')}}
            </td>
        </tr>
        <tr>
            <td colspan="4">
                Total recebidos: R$ {{number_format($recebidos, 2, ',', '.')}}
            </td>
            <td colspan="4">
                Total pagos: R$ {{number_format($pagos, 2, ',', '.')}}
            </td>
            <td colspan="4">
                Total: R$ {{number_format($total, 2, ',', '.')}}
            </td>
        </tr> 
    </tfoot>
</table>