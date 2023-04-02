<table class="table table-bordered table-sm">
    <thead>
        <tr class="text-center">
            <th class="col_id">#Id</th>
            <th class="col_favorecido">Favorecido</th>
            <th class="col_descricao">Descrição</th>
            <th class="col_plano_conta">Plano de conta</th>            
            <th class="col_caixa">Conta Caixa</th>
            <th class="col_vencimento">Dt vencimento</th>            
            <th class="col_vl_parcela">Vl parcela</th>
            <th class="col_dt_compensacao">Dt compensação</th>
            <th class="col_forma_pg">Forma pagamento</th>
            <th class="col_dt_pgto">Dt Pgto</th>
            <th class="col_vl_pgto">Vl pgto</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dados as $item)
            <tr >
                <td class="col_id">{{$item->id}}</td>
                <td class="col_favorecido">
                    @if ($item->prestador)
                        Prestador: {{$item->prestador->nome}};
                    @elseif ($item->paciente)
                        Paciente: {{$item->paciente->nome}};
                    @elseif($item->fornecedor)
                        Fornecedor: {{(!empty($item->fornecedor->nome_fantasia)) ? $item->fornecedor->nome_fantasia : $item->fornecedor->nome}}
                    @endif
                </td>
                <td class="col_descricao">{{$item->descricao}}</td>
                <td class="col_plano_conta">{{$item->planoConta ? $item->planoConta->codigo." ".$item->planoConta->descricao : '-'}}</td>
                <td class="col_caixa">{{(!empty($item->contaCaixa)) ? $item->contaCaixa->descricao : "-"}}</td>                
                <td class="col_vencimento">{{\Carbon\Carbon::createFromFormat('Y-m-d',$item->data_vencimento)->format('d/m/Y')}}</td>
                <td class="col_vl_parcela">{{number_format($item->valor_parcela, 2, ',','.')}}</td>
                <td class="col_dt_compensacao">{{$item->data_compensacao ? \Carbon\Carbon::createFromFormat('Y-m-d',$item->data_compensacao)->format('d/m/Y') : '-'}}</td>
                <td class="col_forma_pg">{{($item->forma_pagamento) ? App\ContaPagar::forma_pagamento_texto($item->forma_pagamento) : '-'}}</td>
                <td class="col_dt_pgto">{{\Carbon\Carbon::createFromFormat('Y-m-d',$item->data_pago)->format('d/m/Y')}}</td>
                <td class="col_vl_pgto">{{number_format($item->valor_pago, 2, ',','.')}}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6">Total</th>
            <th>{{number_format($dados->sum('valor_parcela'), 2, ',', '.')}}</th> 
            <th colspan="3"></th>
            <th>{{number_format($dados->sum('valor_pago'), 2, ',', '.')}}</th> 
        </tr> 
    </tfoot>
</table>